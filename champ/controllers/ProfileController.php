<?php
namespace champ\controllers;

use common\models\Athlete;
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
		$form = new Participant();
		$form->load(\Yii::$app->request->post());
		if (!$form->validate()) {
			return var_dump($form->errors);
		}
		$old = Participant::findOne(['athleteId' => $form->athleteId, 'motorcycleId' => $form->motorcycleId,
		                             'stageId' => $form->stageId]);
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
		
		if ($form->save()) {
			return true;
		} else {
			return var_dump($form->errors);
		}
	}
}