<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use admin\models\MergeAthletesForm;
use admin\models\MergeMotorcyclesForm;
use common\models\Athlete;
use common\models\ClassHistory;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\NewsSubscription;
use common\models\Notice;
use common\models\Participant;
use common\models\RequestForSpecialStage;
use common\models\TmpAthlete;
use common\models\TmpFigureResult;
use common\models\TmpParticipant;
use yii\web\NotFoundHttpException;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class DeveloperController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->can('developer');
	}
	
	public function actionRepeatAthletes()
	{
		$lastNames = Athlete::find()->select('lastName')->groupBy('lastName')->having('count(*) > 1')->asArray()->column();
		$athletes = Athlete::find()->where(['lastName' => $lastNames])->orderBy(['lastName' => SORT_ASC])->all();
		
		return $this->render('repeat-athletes', [
			'athletes' => $athletes
		]);
	}
	
	public function actionRepeatFiguresTime()
	{
		$repeats = FigureTime::find()->select(['athleteId', 'resultTime', 'figureId'])->groupBy(['athleteId', 'resultTime', 'figureId'])
			->having('count(*) > 1')->asArray()->all();
		$items = [];
		foreach ($repeats as $repeat) {
			$items = array_merge($items, FigureTime::findAll(['figureId' => $repeat['figureId'], 'athleteId' => $repeat['athleteId'], 'resultTime' => $repeat['resultTime']]));
		}
		
		return $this->render('repeat-figures-time', [
			'items' => $items
		]);
	}
	
	public function actionLogs($modelClass, $modelId)
	{
		$model = $modelClass::findOne($modelId);
		if (!$model) {
			throw new NotFoundHttpException('Модель не найдена');
		}
		
		return $this->render('//competitions/common/_logs', ['model' => $model]);
	}
	
	public function actionMergeMotorcycles()
	{
		return $this->render('merge-motorcycles');
	}
	
	public function actionMergeMotorcyclesSecond($athleteId)
	{
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			throw new NotFoundHttpException('Спортсмен не найден');
		}
		$formModel = new MergeMotorcyclesForm();
		$formModel->athleteId = $athleteId;
		$errors = null;
		if ($formModel->load(\Yii::$app->request->post())) {
			$firstMotorcycle = Motorcycle::findOne($formModel->firstMotorcycles);
			$secondMotorcycle = Motorcycle::findOne($formModel->secondMotorcycles);
			if ($firstMotorcycle->athleteId != $athlete->id || $secondMotorcycle->athleteId != $athlete->id) {
				$errors = 'Мотоциклы принадлежат другому спортсмену';
			} else {
				if ($firstMotorcycle->id == $secondMotorcycle->id) {
					$errors = 'Мотоциклы совпадают';
				} else {
					$transaction = \Yii::$app->db->beginTransaction();
					FigureTime::updateAll(['motorcycleId' => $firstMotorcycle->id], ['motorcycleId' => $secondMotorcycle->id]);
					Participant::updateAll(['motorcycleId' => $firstMotorcycle->id], ['motorcycleId' => $secondMotorcycle->id]);
					TmpFigureResult::updateAll(['motorcycleId' => $firstMotorcycle->id], ['motorcycleId' => $secondMotorcycle->id]);
					ClassHistory::updateAll(['motorcycleId' => $firstMotorcycle->id], ['motorcycleId' => $secondMotorcycle->id]);
					RequestForSpecialStage::updateAll(['motorcycleId' => $firstMotorcycle->id], ['motorcycleId' => $secondMotorcycle->id]);
					
					if ($secondMotorcycle->delete()) {
						echo 'success' . PHP_EOL;
					}
					$transaction->commit();
					
					return $this->redirect(['merge-motorcycles']);
				}
			}
		}
		
		return $this->render('merge-motorcycles-second', [
			'formModel' => $formModel,
			'athlete'   => $athlete,
			'errors'    => $errors
		]);
	}
	
	public function actionMergeAthletes()
	{
		$formModel = new MergeAthletesForm();
		
		return $this->render('merge-athletes', [
			'formModel' => $formModel
		]);
	}
	
	public function actionSecondStepMerge()
	{
		$formModel = new MergeAthletesForm();
		if ($formModel->load(\Yii::$app->request->post())) {
			if (!$formModel->firstAthleteId || !$formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Выберите спортсменов</div>';
			}
			if ($formModel->firstAthleteId == $formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Ошибка! В качестве первого и второго спортсмена выбран один и тот же</div>';
			}
			$firstAthlete = Athlete::findOne($formModel->firstAthleteId);
			if (!$firstAthlete) {
				return '<div class="alert alert-danger">Первый спортсмен не найден</div>';
			}
			$secondAthlete = Athlete::findOne($formModel->secondAthleteId);
			if (!$secondAthlete) {
				return '<div class="alert alert-danger">Второй спортсмен не найден</div>';
			}
			if ($firstAthlete->hasAccount && $secondAthlete->hasAccount && $firstAthlete->email != $secondAthlete->email) {
				return '<div class="alert alert-danger">Ошибка! У обоих спортсменов есть аккаунты с разной почтой</div>';
			}
			
			return $this->renderAjax('second-step-merge', [
				'formModel'     => $formModel,
				'firstAthlete'  => $firstAthlete,
				'secondAthlete' => $secondAthlete
			]);
		}
		
		return '<div class="alert alert-danger">Возникла ошибка при отправке данных</div>';
	}
	
	public function actionAppendMotorcycle($i, $firstAthleteId, $secondAthleteId)
	{
		$firstAthlete = Athlete::findOne($firstAthleteId);
		$secondAthlete = Athlete::findOne($secondAthleteId);
		
		return $this->renderAjax('_append', [
			'i'             => $i + 1,
			'firstAthlete'  => $firstAthlete,
			'secondAthlete' => $secondAthlete
		]);
	}
	
	public function actionCheckBeforeMerge()
	{
		$formModel = new MergeAthletesForm();
		if ($formModel->load(\Yii::$app->request->post())) {
			if (!$formModel->firstAthleteId || !$formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Выберите спортсменов</div>';
			}
			if ($formModel->firstAthleteId == $formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Ошибка! В качестве первого и второго спортсмена выбран один и тот же</div>';
			}
			
			//Спортсмены
			$firstAthlete = Athlete::findOne($formModel->firstAthleteId);
			if (!$firstAthlete) {
				return '<div class="alert alert-danger">Первый спортсмен не найден</div>';
			}
			$secondAthlete = Athlete::findOne($formModel->secondAthleteId);
			if (!$secondAthlete) {
				return '<div class="alert alert-danger">Второй спортсмен не найден</div>';
			}
			
			//Итоговые мотоциклы
			$firstMotorcycleIds = \Yii::$app->request->post('firstMotorcycles');
			$secondMotorcycleIds = \Yii::$app->request->post('secondMotorcycles');
			$motorcyclesForMerge = [];
			$motorcyclesForMergeIds = [];
			foreach ($firstMotorcycleIds as $i => $firstMotorcycleId) {
				if ($firstMotorcycleId) {
					if (isset($secondMotorcycleIds[$i])) {
						if (in_array($firstMotorcycleId, $motorcyclesForMergeIds) || in_array($secondMotorcycleIds[$i], $motorcyclesForMergeIds)) {
							return '<div class="alert alert-danger">Ошибка! Вы пытаетесь объединить один мотоцикл сразу с несколькими</div>';
						}
						$firstMotorcycle = Motorcycle::findOne($firstMotorcycleId);
						$secondMotorcycle = Motorcycle::findOne($secondMotorcycleIds[$i]);
						if ($firstMotorcycle && $secondMotorcycle) {
							if ($firstMotorcycle->athleteId != $firstAthlete->id || $secondMotorcycle->athleteId != $secondAthlete->id) {
								return '<div class="alert alert-danger">Указанные Мотоциклы не принадлежат этим спортсменам</div>';
							}
							$motorcyclesForMerge[] = [
								'first'  => $firstMotorcycle,
								'second' => $secondMotorcycle
							];
							$motorcyclesForMergeIds[] = $firstMotorcycle->id;
							$motorcyclesForMergeIds[] = $secondMotorcycle->id;
						}
					}
				}
			}
			$otherMotorcycles = [];
			foreach ($firstAthlete->motorcycles as $motorcycle) {
				if (!in_array($motorcycle->id, $motorcyclesForMergeIds)) {
					$otherMotorcycles[] = $motorcycle;
				}
			}
			foreach ($secondAthlete->motorcycles as $motorcycle) {
				if (!in_array($motorcycle->id, $motorcyclesForMergeIds)) {
					$otherMotorcycles[] = $motorcycle;
				}
			}
			
			//Итоговый класс
			if ($firstAthlete->athleteClass->percent < $secondAthlete->athleteClass->percent) {
				$formModel->resultClass = $firstAthlete->athleteClass;
			} elseif ($secondAthlete->athleteClass->percent < $firstAthlete->athleteClass->percent) {
				$formModel->resultClass = $secondAthlete->athleteClass;
			} else {
				if ($firstAthlete->athleteClass->id <= $secondAthlete->athleteClass->id) {
					$formModel->resultClass = $firstAthlete->athleteClass;
				} else {
					$formModel->resultClass = $secondAthlete->athleteClass;
				}
			}
			
			//Номер
			if ($firstAthlete->number) {
				$formModel->number = $firstAthlete->number;
			} elseif ($secondAthlete->number) {
				$formModel->number = $secondAthlete->number;
			}
			
			return $this->renderAjax('_check_step', [
				'formModel'           => $formModel,
				'firstAthlete'        => $firstAthlete,
				'secondAthlete'       => $secondAthlete,
				'motorcyclesForMerge' => $motorcyclesForMerge,
				'otherMotorcycles'    => $otherMotorcycles
			]);
		}
		
		return '<div class="alert alert-danger">Возникла ошибка при отправке данных</div>';
	}
	
	public function actionConfirmMerge()
	{
		$formModel = new MergeAthletesForm();
		if ($formModel->load(\Yii::$app->request->post())) {
			if (!$formModel->firstAthleteId || !$formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Выберите спортсменов</div>';
			}
			if ($formModel->firstAthleteId == $formModel->secondAthleteId) {
				return '<div class="alert alert-danger">Ошибка! В качестве первого и второго спортсмена выбран один и тот же</div>';
			}
			
			//Спортсмены
			$firstAthlete = Athlete::findOne($formModel->firstAthleteId);
			if (!$firstAthlete) {
				return '<div class="alert alert-danger">Первый спортсмен не найден</div>';
			}
			$secondAthlete = Athlete::findOne($formModel->secondAthleteId);
			if (!$secondAthlete) {
				return '<div class="alert alert-danger">Второй спортсмен не найден</div>';
			}
			
			//Итоговые мотоциклы
			$firstMotorcycleIds = \Yii::$app->request->post('firstMotorcycles');
			$secondMotorcycleIds = \Yii::$app->request->post('secondMotorcycles');
			
			$transaction = \Yii::$app->db->beginTransaction();
			//Объединяем мотоциклы
			foreach ($firstMotorcycleIds as $i => $firstMotorcycleId) {
				$secondMotorcycleId = $secondMotorcycleIds[$i];
				FigureTime::updateAll(['motorcycleId' => $firstMotorcycleId], ['motorcycleId' => $secondMotorcycleId]);
				Participant::updateAll(['motorcycleId' => $firstMotorcycleId], ['motorcycleId' => $secondMotorcycleId]);
				TmpFigureResult::updateAll(['motorcycleId' => $firstMotorcycleId], ['motorcycleId' => $secondMotorcycleId]);
				ClassHistory::updateAll(['motorcycleId' => $firstMotorcycleId], ['motorcycleId' => $secondMotorcycleId]);
				RequestForSpecialStage::updateAll(['motorcycleId' => $firstMotorcycleId], ['motorcycleId' => $secondMotorcycleId]);
				
				$secondMotorcycle = Motorcycle::findOne($secondMotorcycleIds[$i]);
				if (!$secondMotorcycle->delete()) {
					$transaction->rollBack();
					
					return 'Возникла ошибка при удалении мотоцикла';
				}
			}
			//Перемещаем остальные мотоциклы
			Motorcycle::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			
			//Итоговый класс
			if ($firstAthlete->athleteClass->percent < $secondAthlete->athleteClass->percent) {
			
			} elseif ($secondAthlete->athleteClass->percent < $firstAthlete->athleteClass->percent) {
				$firstAthlete->athleteClassId = $secondAthlete->athleteClassId;
			} else {
				if ($firstAthlete->athleteClassId <= $secondAthlete->athleteClassId) {
				
				} else {
					$firstAthlete->athleteClassId = $secondAthlete->athleteClassId;
				}
			}
			
			//Номер
			if ($firstAthlete->number) {
			} elseif ($secondAthlete->number) {
				$firstAthlete->number = $secondAthlete->number;
			}
			
			//личный кабинет
			if (!$firstAthlete->hasAccount && $secondAthlete->hasAccount) {
				$firstAthlete->hasAccount = $secondAthlete->hasAccount;
				$firstAthlete->email = $secondAthlete->email;
				$firstAthlete->authKey = $secondAthlete->authKey;
				$firstAthlete->passwordHash = $secondAthlete->passwordHash;
			}
			
			//фото
			if (!$firstAthlete->photo && $secondAthlete->photo) {
				$firstAthlete->photo = $secondAthlete->photo;
			}
			
			//почта
			if (!$firstAthlete->email && $secondAthlete->email) {
				$firstAthlete->email = $secondAthlete->email;
			}
			
			//телефон
			if (!$firstAthlete->phone && $secondAthlete->phone) {
				$firstAthlete->phone = $secondAthlete->phone;
			}
			
			FigureTime::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			Motorcycle::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			Notice::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			Participant::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			TmpAthlete::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			TmpFigureResult::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			TmpParticipant::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			ClassHistory::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			RequestForSpecialStage::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			NewsSubscription::updateAll(['athleteId' => $firstAthlete->id], ['athleteId' => $secondAthlete->id]);
			
			if (!$secondAthlete->delete()) {
				$transaction->rollBack();
				
				return 'Возникла ошибка при удалении второго спортсмена';
			}
			
			if (!$firstAthlete->save(false)) {
				$transaction->rollBack();
				
				return 'Возникла ошибка при сохранении спортсмена';
			}
			
			$transaction->commit();
			
			return '<div class="alert alert-success">Спортсмены успешно объединены</div>';
		}
		
		return '<div class="alert alert-danger">Возникла ошибка при отправке данных</div>';
	}
}
