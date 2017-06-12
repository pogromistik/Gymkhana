<?php
namespace champ\controllers;

use champ\models\PasswordForm;
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
use yii\bootstrap\ActiveForm;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ProfileController extends AccessController
{
	public function init()
	{
		parent::init();
		$this->layout = 'full-content';
	}
	
	public function actionIndex($success = false)
	{
		$this->pageTitle = 'Редактирование профиля';
		$athlete = Athlete::findOne(\Yii::$app->user->identity->id);
		if (!$athlete) {
			throw new NotFoundHttpException('Ошибка! Спортсмен не найден');
		}
		
		if (\Yii::$app->request->isAjax && $athlete->load(\Yii::$app->request->post())) {
			\Yii::$app->response->format = Response::FORMAT_JSON;
			
			return ActiveForm::validate($athlete);
		}
		
		if ($athlete->load(\Yii::$app->request->post()) && $athlete->save()) {
			$file = UploadedFile::getInstance($athlete, 'photoFile');
			if ($file && $file->size <= 307200) {
				if ($athlete->photo) {
					$filePath = \Yii::getAlias('@files') . $athlete->photo;
					if (file_exists($filePath)) {
						unlink($filePath);
					}
				}
				$dir = \Yii::getAlias('@files') . '/' . 'athletes';
				if (!file_exists($dir)) {
					mkdir($dir);
				}
				$title = uniqid() . '.' . $file->extension;
				$folder = $dir . '/' . $title;
				if ($file->saveAs($folder)) {
					$athlete->photo = '/athletes/' . $title;
					$athlete->save(false);
				}
			}
			return $this->redirect(['index', 'success' => true]);
		}
		
		$password = new PasswordForm();
		if ($password->load(\Yii::$app->request->post()) && $password->savePassw()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		$motorcycle = new Motorcycle();
		if ($motorcycle->load(\Yii::$app->request->post()) && $motorcycle->save()) {
			return $this->redirect(['index', 'success' => true]);
		}
		
		return $this->render('index', ['athlete' => $athlete, 'success' => $success, 'password' => $password]);
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
		$this->pageTitle = 'Информация о этапах и заявок на участие';
		
		$time = time();
		$newStages = Stage::find()->where(['or', ['<=', 'startRegistration', $time], ['startRegistration' => null]])
			->andWhere(['or', ['endRegistration' => null], ['>=', 'endRegistration', $time]])->all();
		
		$participants = null;
		if ($newStages) {
			$stageIds = ArrayHelper::getColumn($newStages, 'id');
			$participants = Participant::find()->where(['stageId' => $stageIds])
				->andWhere(['athleteId' => \Yii::$app->user->id])
				->orderBy(['status' => SORT_ASC, 'dateAdded' => SORT_ASC])->all();
		} else {
			$stageIds = Stage::find()->select('id')->where(['>=', 'dateOfThe', $time])->andWhere(['status' => Stage::STATUS_END_REGISTRATION])->asArray()->column();
			if ($stageIds) {
				$participants = Participant::find()->where(['stageId' => $stageIds])
					->andWhere(['athleteId' => \Yii::$app->user->id])
					->orderBy(['status' => SORT_ASC, 'dateAdded' => SORT_ASC])->all();
			}
		}
		
		return $this->render('info', [
			'newStages'    => $newStages,
			'participants' => $participants
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
			$bestResults = FigureTime::find();
			$bestResults->from(new Expression('Athletes, (SELECT *, rank() over (partition by "athleteId" order by "resultTime" asc, "dateAdded" asc) n
       from "FigureTimes" where "figureId" = ' . $figure->id . ') A'));
			$bestResults->select('*');
			$bestResults->where(new Expression('n=1'));
			$bestResults->andWhere(new Expression('"Athletes"."id"="athleteId"'));
			$bestResults->orderBy(['a."resultTime"' => SORT_ASC]);
			$bestResults = $bestResults->all();
			$place = 0;
			$i = 0;
			$percent = 0;
			foreach ($bestResults as $bestResult) {
				$i++;
				if ($bestResult->athleteId == $athlete->id) {
					$place = $i;
					break;
				}
			}
			if ($place > 0) {
				$countParticipants = count($bestResults);
				$percent = ($countParticipants - $place) / $countParticipants * 100;
				$percent = round($percent, 0);
			}
			if ($result) {
				$figuresResult[] = [
					'figure'  => $figure,
					'result'  => $result,
					'percent' => $percent
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
	
	public function actionChangeParticipantStatus($id)
	{
		
		$participant = Participant::findOne($id);
		if (!$id || $participant->athleteId != \Yii::$app->user->id) {
			return 'Заявка не найдена';
		}
		
		if ($participant->bestTime) {
			return 'Вы участвуете в этапе. Изменение данных невозможно';
		}
		
		$stage = $participant->stage;
		if ($stage->dateOfThe < time()) {
			return 'В день соревнований изменение данных невозможно';
		}

		if (in_array($stage->status, [Stage::STATUS_PRESENT, Stage::STATUS_CALCULATE_RESULTS, Stage::STATUS_PAST])) {
			return 'Этап начался, изменение данных невозможно';
		}
		
		if ($participant->status == Participant::STATUS_DISQUALIFICATION) {
			return 'Вы были дисквалифицированы. Изменение данных невозможно';
		}
		
		if ($participant->status == Participant::STATUS_CANCEL_ADMINISTRATION) {
			return 'Ваша заявка отклонена. Чтобы узнать подробности, свяжитесь с организатором этапа';
		}
		
		if ($participant->status == Participant::STATUS_ACTIVE) {
			$participant->status = Participant::STATUS_CANCEL_ATHLETE;
		} else {
			$participant->status = Participant::STATUS_ACTIVE;
		}
		
		if (!$participant->save()) {
			return 'Возникла ошибка при сохранении данных';
		}
		
		return true;
	}
	
	public function actionHelp()
	{
		$this->pageTitle = 'Справка';
		
		return $this->render('help');
	}
	
	public function actionStatsByFigure($figureId)
	{
		$figure = Figure::findOne($figureId);
		if (!$figure) {
			throw new NotFoundHttpException('Фигура не найдена');
		}
		$this->pageTitle = 'Результаты по фигуре ' . $figure->title;
		$figuresResult = FigureTime::find()->where(['figureId' => $figure->id, 'athleteId' => \Yii::$app->user->id])
			->orderBy(['date' => SORT_DESC])->all();
		
		return $this->render('stats-by-figure', [
			'figure'        => $figure,
			'figuresResult' => $figuresResult
		]);
	}
}