<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\ClassHistory;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\Participant;
use common\models\Stage;
use common\models\Year;
use yii\base\UserException;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
	
	public function actionInfo()
	{
		$this->pageTitle = 'Информация';
		
		$time = time();
		$newStages = Stage::find()->where(['<=', 'startRegistration', $time])
			->andWhere(['>=', 'endRegistration', $time])->all();
		
		return $this->render('info', [
			'newStages' => $newStages
		]);
	}
	
	public function actionCheckCompareWith()
	{
		$athleteIds = \Yii::$app->request->post('athleteId');
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error' => false,
			'data'  => null
		];
		if (!$athleteIds) {
			$result['error'] = 'Выберите спортсмена';
			
			return $result;
		}
		
		if ($athleteIds == \Yii::$app->user->id) {
			$result['error'] = 'Вы не можете сравнивать себя с самим собой';
			
			return $result;
		}
		
		if (count($athleteIds) > 2) {
			$result['error'] = 'Максимум можно выбрать 2 спортсменов';
			
			return $result;
		}
		
		$year = \Yii::$app->request->post('year');
		$yearModel = null;
		if ($year) {
			$yearModel = Year::findOne(['year' => $year]);
			if (!$yearModel) {
				$result['error'] = 'Год не найден';
				
				return $result;
			}
		}
		
		foreach ($athleteIds as $athleteId) {
			$athlete = Athlete::findOne($athleteId);
			if (!$athlete) {
				$result['error'] = 'Спортсмен не найден';
				
				return $result;
			}
			
			if ($athleteId == \Yii::$app->user->id) {
				$result['error'] = 'Вы не можете сравнивать себя с самим собой';
				
				return $result;
			}
		}
		
		$me = Athlete::findOne(\Yii::$app->user->id);
		/** @var Athlete[] $athletes */
		$athletes = Athlete::find()->where(['id' => $athleteIds])->orderBy(['id' => SORT_ASC])->all();
		$bestPercentOfClass = $me->athleteClass->percent;
		$bestClassIds = [];
		$bestClass = $me->athleteClassId;
		foreach ($athletes as $athlete) {
			if ($athlete->athleteClassId) {
				$percent = $athlete->athleteClass->percent;
				if ($bestPercentOfClass > $percent) {
					$bestClass = $athlete->athleteClassId;
				}
			}
		}
		if ($bestClass) {
			if ($me->athleteClassId == $bestClass) {
				$bestClassIds[] = $me->id;
			}
			foreach ($athletes as $athlete) {
				if ($athlete->athleteClassId == $bestClass) {
					$bestClassIds[] = $athlete->id;
				}
			}
		}
		
		/** @var Figure[] $figures */
		$figures = Figure::find()->all();
		$figuresStats = [];
		foreach ($figures as $figure) {
			$hisResults = [];
			$meResult = FigureTime::find()->where(['figureId' => $figure->id]);
			if ($year) {
				$meResult = $meResult->andWhere(['yearId' => $yearModel->id]);
			}
			$meResult = $meResult->andWhere(['athleteId' => $me->id])->orderBy(['resultTime' => SORT_ASC])->one();
			if ($meResult) {
				$bestTime = $meResult->resultTime;
				$bestId = $me->id;
				foreach ($athleteIds as $athleteId) {
					$hisResult = FigureTime::find()->where(['figureId' => $figure->id]);
					if ($year) {
						$hisResult = $hisResult->andWhere(['yearId' => $yearModel->id]);
					}
					$hisResult = $hisResult->andWhere(['athleteId' => $athleteId])->orderBy(['resultTime' => SORT_ASC])->one();
					if ($hisResult) {
						$hisResults[$athleteId] = $hisResult;
						if ($hisResult->resultTime < $bestTime) {
							$bestTime = $hisResult;
							$bestId = $athleteId;
						}
					}
					
				}
				ksort($hisResults);
			}
			
			if ($meResult && count($hisResults) == count($athleteIds)) {
				$figuresStats[$figure->id] = [
					'me'     => $meResult,
					'his'    => $hisResults,
					'figure' => $figure,
					'bestId' => $bestId
				];
			}
		}
		
		$stageIds = Participant::find()->select('stageId')
			->where(['athleteId' => $me->id, 'status' => Participant::STATUS_ACTIVE])->andWhere(['not', ['bestTime' => null]])
			->distinct()->asArray()->column();
		foreach ($athleteIds as $athleteId) {
			$hisStageIds = Participant::find()->select('stageId')
				->where(['athleteId' => $athleteId, 'status' => Participant::STATUS_ACTIVE])->andWhere(['not', ['bestTime' => null]])
				->distinct()->asArray()->column();
			$stageIds = array_uintersect($stageIds, $hisStageIds, "strcasecmp");
		}
		
		if ($year) {
			$stages = Stage::find();
			$stages->from(['a' => Stage::tableName(), 'b' => Championship::tableName()]);
			$stages->select('"a".*');
			$stages->where(['"a"."id"' => $stageIds]);
			$stages->andWhere(['"b"."yearId"' => $yearModel->id]);
			$stages->andWhere(new Expression('"a"."championshipId" = "b"."id"'));
			$stages = $stages->all();
		} else {
			$stages = Stage::find()->where(['id' => $stageIds])->all();
		}
		/** @var Stage[] $stages */
		$stagesStats = [];
		foreach ($stages as $stage) {
			$meResult = Participant::find()->where(['stageId' => $stage->id])
				->andWhere(['athleteId' => $me->id, 'status' => Participant::STATUS_ACTIVE])
				->andWhere(['not', ['bestTime' => null]])->all();
			
			$bestTime = null;
			$bestParticipantId = null;
			foreach ($meResult as $item) {
				if (!$bestTime || $item->bestTime < $bestTime) {
					$bestTime = $item->bestTime;
					$bestParticipantId = $item->id;
				}
			}
			//die();
			$hisResults = [];
			foreach ($athleteIds as $athleteId) {
				$hisResult = Participant::find()->where(['stageId' => $stage->id])
					->andWhere(['athleteId' => $athleteId, 'status' => Participant::STATUS_ACTIVE])
					->andWhere(['not', ['bestTime' => null]])->all();
				$hisResults[$athleteId] = $hisResult;
				foreach ($hisResult as $item) {
					if (!$bestTime || $item->bestTime < $bestTime) {
						$bestTime = $item->bestTime;
						$bestParticipantId = $item->id;
					}
				}
			}
			
			ksort($hisResults);
			
			/*if (count($meResult) == 1 && count($hisResult) == 1) {
				$meResult = reset($meResult);
				$hisResult = reset($hisResult);
				$isOneResult = true;
			}*/
			$stagesStats[$stage->id] = [
				'stage'             => $stage,
				'me'                => $meResult,
				'his'               => $hisResults,
				'bestParticipantId' => $bestParticipantId
			];
		}
		
		$result['data'] = $this->renderAjax('result', [
			'athletes'     => $athletes,
			'me'           => $me,
			'figuresStats' => $figuresStats,
			'year'         => $year,
			'stagesStats'  => $stagesStats,
			'bestClassIds' => $bestClassIds
		]);
		
		return $result;
	}
	
	public function actionStats()
	{
		$this->pageTitle = 'Статистика';
		
		$athlete = Athlete::findOne(\Yii::$app->user->id);
		/** @var Figure[] $figures */
		$figures = Figure::find()->orderBy(['title' => SORT_ASC])->all();
		$figuresResult = [];
		foreach ($figures as $figure) {
			$result = FigureTime::find()->where(['figureId' => $figure->id, 'athleteId' => $athlete->id])
				->orderBy(['resultTime' => SORT_ASC])->one();
			if ($result) {
				$figuresResult[] = [
					'figure' => $figure,
					'result' => $result
				];
			}
		}
		
		$history = ClassHistory::find()->where(['athleteId' => $athlete->id])->orderBy(['date' => SORT_ASC]);
		$count = $history->count();
		if ($count > 15) {
			$offset = $count - 15;
			$history = $history->offset($offset);
		}
		$history = $history->all();
		
		return $this->render('stats', [
			'figuresResult' => $figuresResult,
			'history'       => $history,
			'athlete'       => $athlete,
		]);
	}
	
	public function actionDeletePhoto()
	{
		$athlete = Athlete::findOne(\Yii::$app->user->id);
		if ($athlete->photo) {
			$filePath = \Yii::getAlias('@files') . $athlete->photo;
			if (file_exists($filePath)) {
				unlink($filePath);
			}
			$athlete->photo = null;
			if (!$athlete->save()) {
				return 'Возникла ошибка при сохранении изменений';
			}
		}
		
		return true;
	}
}