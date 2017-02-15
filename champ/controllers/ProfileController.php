<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\Motorcycle;
use common\models\Participant;
use yii\web\NotFoundHttpException;

class ProfileController extends AccessController
{
	public function actionIndex($success = false)
	{
		$this->pageTitle = 'Редактирование профиля';
		$athlete = Athlete::findOne(\Yii::$app->user->identity->id);
		if (!$athlete) {
			throw new NotFoundHttpException('Ошибка! Спортсмен не найден');
		}
		
		if ($athlete->load(\Yii::$app->request->post()) && $athlete->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		$motorcycle = new Motorcycle();
		if ($motorcycle->load(\Yii::$app->request->post()) && $motorcycle->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		return $this->render('index', ['athlete' => $athlete, 'success' => $success]);
	}
	
	public function actionChangeStatus($id)
	{
		$motorcycle = Motorcycle::findOne($id);
		if (!$motorcycle || $motorcycle->athleteId != \Yii::$app->user->identity->id) {
			return 'Мотоцикл не найден';
		}
		if ($motorcycle->status) {
			$motorcycle->status = Motorcycle::STATUS_INACTIVE;
		} else {
			$motorcycle->status = Motorcycle::STATUS_ACTIVE;
		}
		
		if ($motorcycle->save()) {
			return true;
		}
		
		return 'Возникла ошибка при изменении данных';
	}
	
	public function actionAddRegistration()
	{
		if (\Yii::$app->user->isGuest) {
			return 'Сначала войдите в личный кабинет';
		}
		
		$form = new Participant();
		$form->load(\Yii::$app->request->post());
		if (!$form->validate()) {
			return var_dump($form->errors);
		}
		
		$stage = $form->stage;
		if (time() < $stage->startRegistration) {
			return 'Регистрация на этап начнётся ' . $stage->startRegistrationHuman;
		}
		
		if (time() > $stage->endRegistration) {
			return 'Регистрация на этап завершилась.';
		}
		
		$old = Participant::findOne(['athleteId' => $form->athleteId, 'motorcycleId' => $form->motorcycleId,
		                             'stageId'   => $form->stageId]);
		if ($old) {
			if ($old->status != Participant::STATUS_ACTIVE) {
				$old->status = Participant::STATUS_ACTIVE;
				if ($old->save()) {
					return true;
				}
				
				return var_dump($old->errors);
			}
			
			return 'Вы уже зарегистрированы на этот этап на этом мотоцикле.';
		}
		
		$championship = $stage->championship;
		$athlete = Athlete::findOne($form->athleteId);
		if (\Yii::$app->mutex->acquire('setNumber' . $stage->id, 10)) {
			if ($form->number) {
				$freeNumbers = Championship::getFreeNumbers($stage);
				if (!in_array($form->number, $freeNumbers)) {
					return 'Номер занят. Выберите другой или оставьте поле пустым.';
				}
			} elseif ($athlete->number && $championship->regionId && $athlete->city->regionId == $championship->regionId) {
				$form->number = $athlete->number;
			} else {
				$freeNumbers = Championship::getFreeNumbers($stage);
				if ($freeNumbers) {
					$form->number = $freeNumbers[0];
				}
			}
			if ($form->save()) {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				return true;
			} else {
				\Yii::$app->mutex->release('setNumber' . $stage->id);
				return var_dump($form->errors);
			}
		}
		\Yii::$app->mutex->release('setNumber' . $stage->id);
		return 'Внутренняя ошибка. Пожалуйста, попробуйте позже.';
	}
}