<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\ClassHistory;
use common\models\Motorcycle;
use common\models\Notice;
use common\models\Stage;
use common\models\Time;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Participant;
use common\models\search\ParticipantSearch;
use yii\base\UserException;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ParticipantsController implements the CRUD actions for Participant model.
 */
class ParticipantsController extends BaseController
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => Participant::className(),
				'forceCreate' => false
			]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	public function actionIndex($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		$error = null;
		$needClarification = false;
		
		$searchModel = new ParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['stageId' => $stageId]);
		
		$forSearch = Participant::find()->from(['a' => Participant::tableName(), 'b' => Athlete::tableName()])
			->select(['a."athleteId"', '(b."lastName" || \' \' || b."firstName") as "name"'])
			->where(new Expression('"a"."athleteId" = "b"."id"'))
			->andWhere(['stageId' => $stageId])
			->orderBy(['name' => SORT_ASC])
			->distinct()
			->asArray()->all();
		if ($forSearch) {
			$forSearch = ArrayHelper::map($forSearch, 'athleteId', 'name');
		}
		
		$participant = new Participant();
		$participant->stageId = $stage->id;
		$participant->championshipId = $stage->championshipId;
		if ($participant->load(Yii::$app->request->post())) {
			$old = Participant::findOne(['athleteId' => $participant->athleteId, 'motorcycleId' => $participant->motorcycleId,
			                             'stageId'   => $participant->stageId]);
			if ($old) {
				$error = 'Участник уже зарегистрирован на этот этап.';
				if ($old->status != Participant::STATUS_ACTIVE) {
					$error .= ' Сейчас его заявка отменена. Чтобы вернуть её, нажмите на значок <span class="fa fa-check btn-success"></span> 
 в заявке участника';
				}
			} else {
				$athlete = $participant->athlete;
				$championship = $participant->championship;
				if ($participant->number) {
					$freeNumbers = Championship::getFreeNumbers($stage, $participant->athleteId);
					if (!in_array($participant->number, $freeNumbers)) {
						$error .= 'Номер занят. Выберите другой или оставьте поле пустым.';
					}
				} elseif ($athlete->number && $championship->regionId && $athlete->city->regionId == $championship->regionId) {
					$participant->number = $athlete->number;
				}
			}
			if ($participant->status != Participant::STATUS_OUT_COMPETITION) {
				if ($stage->participantsLimit > 0) {
					$count = Participant::find()->where(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_DISQUALIFICATION]])
						->andWhere(['stageId' => $stage->id])->count();
					if ($count >= $stage->participantsLimit) {
						$confirmed = \Yii::$app->request->post('confirmed');
						if (!$confirmed) {
							$needClarification = true;
						}
					}
				}
				$participant->status = Participant::STATUS_ACTIVE;
				if ($stage->dateOfThe <= time()) {
					$participant->isArrived = true;
				}
			}
			if ($error) {
				$needClarification = false;
			}
			if (!$error && !$needClarification && $participant->save()) {
				return $this->redirect(['index', 'stageId' => $stageId]);
			}
		}
		
		return $this->render('index', [
			'searchModel'       => $searchModel,
			'dataProvider'      => $dataProvider,
			'stage'             => $stage,
			'participant'       => $participant,
			'error'             => $error,
			'forSearch'         => $forSearch,
			'needClarification' => $needClarification
		]);
	}
	
	public function actionSort($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		$query = new Query();
		$query->from(['a' => Participant::tableName(), 'd' => AthletesClass::tableName(), 'b' => Athlete::tableName(), 'c' => Motorcycle::tableName()]);
		$query->where(['a.stageId' => $stageId]);
		$query->select(['a."id", b."lastName", b."firstName", c."mark", c."model", a."number", d."title"']);
		$query->andWhere(['a.status' => [Participant::STATUS_ACTIVE, Participant::STATUS_OUT_COMPETITION]]);
		$query->andWhere(new Expression('"a"."athleteId" = "b"."id"'));
		$query->andWhere(new Expression('"a"."motorcycleId" = "c"."id"'));
		$query->andWhere(new Expression('"a"."athleteClassId" = "d"."id"'));
		$query->orderBy(['a.isArrived' => SORT_DESC, 'a.sort' => SORT_ASC, 'a.id' => SORT_ASC]);
		$participants = $query->all();
		$participantsArray = [];
		foreach ($participants as $participant) {
			$content = $participant['lastName'] . ' ' . $participant['firstName'];
			$content .= ', ' . $participant['title'];
			if (isset($participant['number'])) {
				$content .= ', №' . $participant['number'];
			}
			$content .= ', ' . $participant['mark'] . ' ' . $participant['model'];
			$participantsArray[$participant['id']] = ['content' => $content];
		}
		
		return $this->render('sort', ['participantsArray' => $participantsArray, 'stage' => $stage]);
	}
	
	public function actionChangeSort()
	{
		$this->can('competitions');
		
		$sortList = \Yii::$app->request->getBodyParam('sort_list');
		$sortItems = explode(',', $sortList);
		$transaction = \Yii::$app->db->beginTransaction();
		$i = 1;
		foreach ($sortItems as $item) {
			$participant = Participant::findOne($item);
			$participant->sort = $i++;
			if (!$participant->save()) {
				$transaction->rollBack();
			}
		}
		$transaction->commit();
		
		return var_dump($sortItems);
	}
	
	/**
	 * Finds the Participant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Participant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = Participant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionMotorcycleCategory()
	{
		$this->can('competitions');
		
		if (isset($_POST['depdrop_parents'])) {
			$parent = $_POST['depdrop_parents'];
			if ($parent != null) {
				$athleteId = $parent[0];
				$out = self::getSubCatList($athleteId);
				echo Json::encode(['output' => $out, 'selected' => '']);
				
				return;
			}
		}
		echo Json::encode(['output' => '', 'selected' => '']);
	}
	
	public function actionMotorcycleCategoryForParticipants()
	{
		$this->can('competitions');
		
		if (isset($_POST['depdrop_parents'])) {
			$parent = $_POST['depdrop_parents'];
			if ($parent != null) {
				$participantId = $parent[0];
				$participant = Participant::findOne($participantId);
				$out = self::getSubCatList($participant->athleteId);
				echo Json::encode(['output' => $out, 'selected' => '']);
				
				return;
			}
		}
		echo Json::encode(['output' => '', 'selected' => '']);
	}
	
	public function getSubCatList($athleteId)
	{
		$this->can('competitions');
		
		$athlete = Athlete::findOne($athleteId);
		$motorcycles = $athlete->getMotorcycles()->andWhere(['status' => Motorcycle::STATUS_ACTIVE])->all();
		$result = [];
		foreach ($motorcycles as $motorcycle) {
			$result[] = ['id' => $motorcycle->id, 'name' => $motorcycle->mark . ' ' . $motorcycle->model];
		}
		
		return $result;
	}
	
	public function actionRaces($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		$error = false;
		
		if (Participant::find()->where(['stageId' => $stage->id])->andWhere(['athleteClassId' => null])->one()) {
			$error = 'Не установлены классы спортсменов';
		}
		
		$participants = $stage->getParticipantsForRaces()->andWhere(['isArrived' => 1])->all();
		
		return $this->render('races', [
			'stage'        => $stage,
			'error'        => $error,
			'participants' => $participants
		]);
	}
	
	public function actionAddTime()
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error'   => false,
			'success' => false,
			'id'      => null
		];
		
		$params = \Yii::$app->request->getBodyParams();
		
		if (isset($params['Time']['id']) && $params['Time']['id'] != '') {
			$time = Time::findOne($params['Time']['id']);
		} else {
			$time = new Time();
		}
		if ($time->load(\Yii::$app->request->post())) {
			$stage = Stage::findOne($time->stageId);
			if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
				if ($stage->regionId != \Yii::$app->user->identity->regionId) {
					$result['error'] = 'Доступ запрещен';
					
					return $result;
				}
			}
			
			if (!$time->timeForHuman) {
				if ($time->isFail == Time::IS_FAIL_YES) {
					$time->timeForHuman = Time::FAIL_TIME_FOR_HUMAN;
				} else {
					$result['error'] = $time->participant->athlete->getFullName() . ': необходимо указать время';
					
					return $result;
				}
			}
			trim($time->timeForHuman, '_');
			if ($time->save()) {
				$result['success'] = true;
				$result['id'] = $time->id;
				
				return $result;
			}
			
			$result['error'] = var_dump($time->errors);
			
			return $result;
		}
		
		$result['error'] = true;
		
		return $result;
	}
	
	public function actionChangeStatus($id, $status, $confirmed = false)
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		
		$result = [
			'text'              => null,
			'success'           => false,
			'error'             => false,
			'needClarification' => false
		];
		$participant = Participant::findOne($id);
		if (!$participant) {
			$result['text'] = 'Заявка не найдена';
			$result['error'] = true;
			
			return $result;
		}
		$stage = $participant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				$result['text'] = 'Доступ запрещен';
				$result['error'] = true;
				
				return $result;
			}
		}
		
		$athlete = $participant->athlete;
		if ($status == Participant::STATUS_ACTIVE && $participant->status != Participant::STATUS_ACTIVE && $stage->participantsLimit > 0) {
			$count = Participant::find()->where(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_DISQUALIFICATION]])
				->andWhere(['stageId' => $stage->id])->count();
			if ($stage->participantsLimit <= $count && !$confirmed) {
				$result['text'] = 'На этап уже зарегистрировано максимальное количество (' . $count . ') человек. Всё равно зарегистрировать этого спортсмена?.';
				$result['needClarification'] = true;
				
				return $result;
			}
			if ($athlete->hasAccount) {
				$text = 'Ваша заявка на этап "' . $stage->title
					. '" чемпионата "' . $stage->championship->title . '" подтверждена';
				Notice::add($participant->athleteId, $text);
			}
		} elseif ($status == Participant::STATUS_CANCEL_ADMINISTRATION && $stage->participantsLimit > 0
			&& $participant->status != Participant::STATUS_CANCEL_ADMINISTRATION
			&& $stage->dateOfThe > time()
		) {
			if ($athlete->hasAccount) {
				$text = 'Ваша заявка на этап "' . $stage->title . '" чемпионата "' . $stage->championship->title . '" отклонена';
				Notice::add($participant->athleteId, $text);
			}
			if (YII_ENV != 'dev' && $athlete->email) {
				$text = 'Ваша заявка на этап "' . $stage->title . '" чемпионата 
				"' . $stage->championship->title . '" на мотоцикле '
					. $participant->motorcycle->getFullTitle() . ' отклонена, так как на этап уже зарегистрировано максимальное
					количество участников. Для уточнения подробностей можете связаться с
					организатором соревнования.';
				if (YII_ENV != 'dev') {
					\Yii::$app->mailer->compose('text', ['text' => $text])
						->setTo($athlete->email)
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup: регистрация на этап отклонена')
						->send();
				}
			}
		} elseif ($status == Participant::STATUS_OUT_COMPETITION && $participant->status != Participant::STATUS_OUT_COMPETITION) {
			if ($athlete->hasAccount) {
				$text = 'Вы допущены на этап "' . $stage->title . '" чемпионата "' . $stage->championship->title . '" вне зачёта';
				Notice::add($participant->athleteId, $text);
			}
		}
		$participant->status = $status;
		
		if ($participant->save()) {
			$result['success'] = true;
			
			return $result;
		}
		
		$result['text'] = 'Возникла ошибка при сохранении изменений. Свяжитесь с разработчиком.';
		$result['error'] = true;
		
		return $result;
	}
	
	public function actionSetClasses($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			return 'Этап не найден';
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		if ($stage->status == Stage::STATUS_PAST) {
			return 'Этап завершен, смена первоначальных классов невозможна';
		} elseif ($stage->status == Stage::STATUS_CALCULATE_RESULTS) {
			return 'Ведётся подсчёт результатов, поэтому смена первоначальных классов в автоматическом режиме невозможна';
		} else {
			$participantsWithModifiedClass = Participant::find()->where(
				['stageId' => $stageId, 'status' => Participant::STATUS_ACTIVE])
				->andWhere(['not', ['newAthleteClassId' => null]])
				->andWhere(['newAthleteClassStatus' => Participant::NEW_CLASS_STATUS_APPROVE])
				->one();
			if ($participantsWithModifiedClass) {
				return 'По результатам этапа некоторые спортсмены перешли в другие классы, поэтому смена первоначальных классов в автоматическом режиме невозможна';
			}
		}
		
		$championship = $stage->championship;
		
		if (!$stage->fastenClassFor || $stage->fastenClassFor == 0 || ($stage->dateOfThe - $stage->fastenClassFor * 86400) >= time()) {
			$participants = Participant::findAll(['stageId' => $stageId]);
			foreach ($participants as $participant) {
				$athlete = $participant->athlete;
				if (!$athlete->athleteClassId) {
					return 'Необходимо сначала установить класс для спортсмена ' . $athlete->getFullName();
				}
				$participant->athleteClassId = $athlete->athleteClassId;
				if ($championship->useCheScheme) {
					$internalClass = $participant->internalClassWithScheme($participant->athleteClassId);
					if ($internalClass) {
						$participant->internalClassId = $internalClass;
					}
				}
				if (!$participant->save()) {
					return 'Не удалось установить класс участнику ' . $athlete->getFullName() . '. Обратитесь к разработчику.';
				}
			}
		}
		
		$participants = Participant::findAll(['stageId' => $stageId, 'status' => [Participant::STATUS_ACTIVE]]);
		if ($participants) {
			$classIds = Participant::find()->select('athleteClassId')
				->where(['stageId' => $stageId, 'status' => Participant::STATUS_ACTIVE])->distinct()->asArray()->column();
			$class = null;
			while ($classIds) {
				$percent = AthletesClass::find()->where(['id' => $classIds])->min('"percent"');
				$presumablyClass = AthletesClass::findOne(['percent' => $percent, 'id' => $classIds]);
				if (Participant::find()->where(['stageId' => $stageId, 'status' => Participant::STATUS_ACTIVE])
						->andWhere(['athleteClassId' => $presumablyClass->id])->count() >= 3
				) {
					$class = $presumablyClass;
					break;
				}
				$key = array_search($presumablyClass->id, $classIds);
				unset($classIds[$key]);
			}
			if (!$class) {
				$class = AthletesClass::find()->where(['status' => AthletesClass::STATUS_ACTIVE])
					->orderBy(['percent' => SORT_DESC])->one();
			}
			$stage->class = $class->id;
			if (!$stage->save()) {
				return 'Не удалось установить класс соревнований';
			}
		}
		
		return true;
	}
	
	public function actionApproveClass($id)
	{
		$this->can('competitions');
		
		$participant = Participant::findOne($id);
		if (!$participant) {
			return 'Участник не найден';
		}
		$stage = $participant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		$result = $this->approveClassForParticipant($participant);
		if ($result !== true) {
			return $result;
		}
		
		return true;
	}
	
	public function actionCancelClass($id)
	{
		$this->can('competitions');
		
		$participant = Participant::findOne($id);
		if (!$participant) {
			return 'Участник не найден';
		}
		$stage = $participant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		if ($participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$participant->newAthleteClassId = null;
		$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_CANCEL;
		if (!$participant->save()) {
			return var_dump($participant->errors);
		}
		
		return true;
	}
	
	public function actionApproveAllClasses($id)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		/** @var Participant[] $participants */
		$participants = $stage->getParticipantsForRaces()->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => Participant::NEW_CLASS_STATUS_NEED_CHECK])->all();
		$errors = null;
		foreach ($participants as $participant) {
			$result = $this->approveClassForParticipant($participant);
			if ($result !== true) {
				$errors .= $result . PHP_EOL . PHP_EOL;
			}
		}
		if ($errors) {
			return $errors;
		}
		
		return true;
	}
	
	public function actionCancelAllClasses($id)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		/** @var Participant[] $participants */
		$participants = $stage->getParticipantsForRaces()->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => Participant::NEW_CLASS_STATUS_NEED_CHECK])->all();
		foreach ($participants as $participant) {
			if ($participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_NEED_CHECK) {
				return 'Запись уже была обработана';
			}
			$participant->newAthleteClassId = null;
			$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_CANCEL;
			if (!$participant->save()) {
				return var_dump($participant->errors);
			}
		}
		
		return true;
	}
	
	public function approveClassForParticipant(Participant $participant)
	{
		$this->can('competitions');
		
		$stage = $participant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		
		if ($participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$athlete = $participant->athlete;
		/*if ($athlete->athleteClass->percent < $participant->newAthleteClass->percent) {
			return 'Вы пытаетесь понизить спортсмену ' . $athlete->getFullName() . ' класс с ' . $athlete->athleteClass->title . ' на '
				. $participant->newAthleteClass->title . '. Скорее всего, участник уже повысил класс по результатам фигур после
				того, как оставил заявку на участие в чемпионате. Понижение класса невозможно';
		}*/
		if ($athlete->athleteClass->percent <= $participant->newAthleteClass->percent) {
			$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_APPROVE;
			if (!$participant->save()) {
				
				return 'Невозможно сохранить изменения для участника. Свяжитесь с разработчиком.';
			}
			
			return true;
		}
		
		if ($athlete->athleteClassId != $participant->newAthleteClassId) {
			$transaction = \Yii::$app->db->beginTransaction();
			
			$event = $participant->championship->title . ', ' . $participant->stage->title;
			$history = ClassHistory::create($athlete->id, $participant->motorcycleId,
				$athlete->athleteClassId, $participant->newAthleteClassId, $event,
				$participant->bestTime, $participant->stage->referenceTime, $participant->percent);
			if (!$history) {
				$transaction->rollBack();
				
				return 'Возникла ошибка при изменении данных. Свяжитесь с разработчиком.';
			}
			
			$athlete->athleteClassId = $participant->newAthleteClassId;
			if (!$athlete->save()) {
				$transaction->rollBack();
				
				return 'Невозможно изменить класс спортсмену ' . $athlete->getFullName() . '. Свяжитесь с разработчиком.';
			}
			
			$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_APPROVE;
			if (!$participant->save()) {
				$transaction->rollBack();
				
				return 'Невозможно сохранить изменения для участника. Свяжитесь с разработчиком.';
			}
			$transaction->commit();
		}
		
		return true;
	}
	
	public function actionIsArrived($id)
	{
		$this->can('competitions');
		$participant = Participant::findOne($id);
		if (!$participant) {
			return 'Заявка не найдена';
		}
		$stage = $participant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещен';
			}
		}
		if ($participant->isArrived) {
			$participant->isArrived = 0;
		} else {
			$participant->isArrived = 1;
		}
		if ($participant->save(false)) {
			return true;
		}
		
		return var_dump($participant->errors);
	}
	
	public function actionSetFinalList($stageId)
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error'   => false,
			'success' => false,
			'text'    => null
		];
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			$result['text'] = 'Этап не найден';
			$result['error'] = true;
			
			return $result;
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				$result['text'] = 'Доступ запрещен';
				$result['error'] = true;
				
				return $result;
			}
		}
		
		$count = Participant::updateAll(['status' => Participant::STATUS_CANCEL_ADMINISTRATION],
			['bestTime' => null, 'stageId' => $stage->id, 'isArrived' => 0,
			 'status'   => [Participant::STATUS_ACTIVE, Participant::STATUS_OUT_COMPETITION, Participant::STATUS_NEED_CLARIFICATION]]);
		
		$result['text'] = 'Отменено заявок: ' . $count;
		$result['success'] = true;
		
		return $result;
	}
}
