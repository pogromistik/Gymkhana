<?php

namespace champ\controllers;

use champ\models\PasswordForm;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\ClassesRequest;
use common\models\ClassHistory;
use common\models\Country;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\NewsSubscription;
use common\models\Participant;
use common\models\search\ClassesRequestSearch;
use common\models\Stage;
use common\models\Year;
use dosamigos\editable\EditableAction;
use yii\base\UserException;
use yii\bootstrap\ActiveForm;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProfileController extends AccessController
{
	public function init()
	{
		parent::init();
		$this->layout = 'full-content';
	}
	
	public function actions()
	{
		return [
			'update-motorcycle' => [
				'class'       => EditableAction::className(),
				'modelClass'  => Motorcycle::className(),
				'forceCreate' => false
			]
		];
	}
	
	public function actionUploadImg()
	{
		$model = Athlete::findOne(\Yii::$app->user->identity->id);
		if (!$model) {
			throw new UserException();
		}
		
		if ($model->load(\Yii::$app->request->post())) {
			$model->save();
			
			return $this->redirect('index');
		}
		
		throw new NotFoundHttpException();
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
		
		$subscription = NewsSubscription::findOne(['athleteId' => $athlete->id, 'isActive' => NewsSubscription::IS_ACTIVE_YES]);
		if (!$subscription) {
			$subscription = new NewsSubscription();
		} else {
			$subscription->regionIds = $subscription->getRegionIds();
			$subscription->countryIds = $subscription->getCountryIds();
			$subscription->types = $subscription->getTypes();
		}
		
		return $this->render('index', [
			'athlete'      => $athlete,
			'success'      => $success,
			'password'     => $password,
			'subscription' => $subscription
		]);
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
		$withoutRegistrationIds = Stage::find()->select('id')
			->where(['startRegistration' => null, 'endRegistration' => null])
			->andWhere(['not', ['status' => Stage::STATUS_CANCEL]])->asArray()->column();
		
		$newStages = Stage::find()->where(['or', ['<=', 'startRegistration', $time], ['startRegistration' => null]])
			->andWhere(['or', ['endRegistration' => null], ['>=', 'endRegistration', $time]])
			->andWhere(['not', ['status' => Stage::STATUS_CANCEL]])->andWhere(['registrationFromSite' => 1]);
		if ($withoutRegistrationIds) {
			$newStages->andWhere(['not', ['id' => $withoutRegistrationIds]]);
		}
		$newStages = $newStages->all();
		
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
					$bestPercentOfClass = $percent;
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
							$bestTime = $hisResult->resultTime;
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
		
		if ($participant->status == Participant::STATUS_ACTIVE || $participant->status == Participant::STATUS_NEED_CLARIFICATION
			|| $participant->status == Participant::STATUS_OUT_COMPETITION
		) {
			$participant->status = Participant::STATUS_CANCEL_ATHLETE;
		} elseif ($stage->participantsLimit && $stage->participantsLimit > 0) {
			$participant->status = Participant::STATUS_NEED_CLARIFICATION;
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
	
	public function actionChangeClass()
	{
		$this->pageTitle = 'Отправить запрос на изменение класса';
		
		$model = new ClassesRequest();
		$model->athleteId = \Yii::$app->user->id;
		
		$classes = AthletesClass::find()->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])->all();
		
		return $this->render('change-class', [
			'model'   => $model,
			'classes' => $classes
		]);
	}
	
	public function actionSendClassRequest()
	{
		$model = new ClassesRequest();
		
		if ($model->load(\Yii::$app->request->post())) {
			if (!$model->newClassId) {
				return 'Выберите класс';
			}
			if (!$model->comment) {
				return 'Укажите причину смены класса';
			}
			if ($model->save()) {
				return true;
			}
		}
		
		return 'Возникла ошибка при отправке данных';
	}
	
	public function actionHistoryClassesRequest()
	{
		$searchModel = new ClassesRequestSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['athleteId' => \Yii::$app->user->id]);
		
		$this->pageTitle = 'Заявки на изменение класса';
		$this->layout = 'full-content';
		
		return $this->render('history-classes-request', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionSubscriptions()
	{
		$athlete = Athlete::findOne(\Yii::$app->user->identity->id);
		if (!$athlete) {
			throw new NotFoundHttpException('Ошибка! Спортсмен не найден');
		}
		$isActive = \Yii::$app->request->post('subscription');
		$subscription = NewsSubscription::findOne(['athleteId' => $athlete->id, 'isActive' => NewsSubscription::IS_ACTIVE_YES]);
		if (!$isActive && !$subscription) {
			return true;
		}
		if (!$subscription) {
			$subscription = new NewsSubscription();
		} elseif (!$isActive) {
			$subscription->isActive = NewsSubscription::IS_ACTIVE_NO;
			$subscription->dateEnd = time();
			if (!$subscription->save()) {
				return var_dump($subscription->errors);
			}
			
			return true;
		}
		$subscription->load(\Yii::$app->request->post());
		if ($subscription->regionIds) {
			$subscription->regionIds = array_map(function ($item) {
				return (int)$item;
			}, $subscription->regionIds);
			$subscription->regionIds = json_encode($subscription->regionIds);
		} else {
			$subscription->regionIds = null;
		}
		if ($subscription->countryIds) {
			if (count($subscription->countryIds) == count(Country::find()->all())) {
				$subscription->countryIds = null;
				$subscription->regionIds = null;
			} else {
				$subscription->countryIds = array_map(function ($item) {
					return (int)$item;
				}, $subscription->countryIds);
				$subscription->countryIds = json_encode($subscription->countryIds);
			}
		} else {
			$subscription->countryIds = null;
		}
		if ($subscription->types) {
			$subscription->types = array_map(function ($item) {
				return (int)$item;
			}, $subscription->types);
			$subscription->types = json_encode($subscription->types);
		} else {
			$subscription->types = null;
		}
		if (!$subscription->save()) {
			return var_dump($subscription->errors);
		}
		
		return true;
	}
}