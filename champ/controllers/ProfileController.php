<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
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
		$athleteId = \Yii::$app->request->post('athleteId');
		if (!$athleteId) {
			return 'Выберите спортсмена';
		}
		
		if ($athleteId == \Yii::$app->user->id) {
			return 'Вы не можете сравнивать себя с самим собой';
		}
		
		if (!Athlete::findOne($athleteId)) {
			return 'Спортсмен не найден';
		}
		
		return true;
	}
	
	public function actionCompareWith($athleteId = null, $year = null)
	{
		if ($athleteId) {
			$athlete = Athlete::findOne($athleteId);
			if (!$athlete) {
				throw new UserException('Спортсмен не найден');
			}
			
			if ($year) {
				$yearModel = Year::findOne(['year' => $year]);
				if (!$yearModel) {
					throw new UserException('Год не найден');
				}
			}
			
			if ($athleteId == \Yii::$app->user->id) {
				throw new UserException('Вы не можете сравнивать себя с самим собой');
			}
			
			$this->pageTitle = 'Сравнение результатов';
			$me = Athlete::findOne(\Yii::$app->user->id);
			
			/** @var Figure[] $figures */
			$figures = Figure::find()->all();
			$figuresStats = [];
			foreach ($figures as $figure) {
				$meResult = FigureTime::find()->where(['figureId' => $figure->id]);
				$hisResult = FigureTime::find()->where(['figureId' => $figure->id]);
				if ($year) {
					$meResult = $meResult->andWhere(['yearId' => $yearModel->id]);
					$hisResult = $hisResult->andWhere(['yearId' => $yearModel->id]);
				}
				$meResult = $meResult->andWhere(['athleteId' => $me->id])->orderBy(['resultTime' => SORT_ASC])->one();
				$hisResult = $hisResult->andWhere(['athleteId' => $athlete->id])->orderBy(['resultTime' => SORT_ASC])->one();
				
				if ($meResult && $hisResult) {
					$figuresStats[$figure->id] = [
						'me'     => $meResult,
						'his'    => $hisResult,
						'figure' => $figure
					];
				}
			}
			
			$meStageIds = Participant::find()->select('stageId')
				->where(['athleteId' => $me->id, 'status' => Participant::STATUS_ACTIVE])->andWhere(['not', ['bestTime' => null]])
				->distinct()->asArray()->column();
			$hisStageIds = Participant::find()->select('stageId')
				->where(['athleteId' => $athlete->id, 'status' => Participant::STATUS_ACTIVE])->andWhere(['not', ['bestTime' => null]])
				->distinct()->asArray()->column();
			$stageIds = array_uintersect($meStageIds, $hisStageIds, "strcasecmp");
			
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
				$hisResult = Participant::find()->where(['stageId' => $stage->id])
					->andWhere(['athleteId' => $athlete->id, 'status' => Participant::STATUS_ACTIVE])
					->andWhere(['not', ['bestTime' => null]])->all();
				$isOneResult = false;
				if (count($meResult) == 1 && count($hisResult) == 1) {
					$meResult = reset($meResult);
					$hisResult = reset($hisResult);
					$isOneResult = true;
				}
				$stagesStats[$stage->id] = [
					'stage'       => $stage,
					'me'          => $meResult,
					'his'         => $hisResult,
					'isOneResult' => $isOneResult
				];
			}
		} else {
			$athlete = null;
			$me = null;
			$figuresStats = null;
			$stagesStats = null;
		}
		
		
		return $this->render('compareWith', [
			'athlete'      => $athlete,
			'me'           => $me,
			'figuresStats' => $figuresStats,
			'year'         => $year,
			'stagesStats'  => $stagesStats,
			'athleteId'    => $athleteId
		]);
	}
}