<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use admin\models\ParticipantForm;
use admin\models\SpecialRequestForm;
use common\models\Athlete;
use common\models\City;
use common\models\ClassHistory;
use common\models\HelpModel;
use common\models\Motorcycle;
use common\models\Notice;
use common\models\Participant;
use common\models\RequestForSpecialStage;
use common\models\search\RequestForSpecialStageSearch;
use common\models\SpecialStage;
use common\models\Stage;
use Yii;
use common\models\SpecialChamp;
use common\models\search\SpecialChampsSearch;
use yii\base\UserException;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpecialChampController implements the CRUD actions for SpecialChamp model.
 */
class SpecialChampController extends BaseController
{
	public function init()
	{
		$this->can('changeSpecialChamps');
		
		return parent::init();
	}
	
	/**
	 * Lists all SpecialChamp models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new SpecialChampsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single SpecialChamp model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new SpecialChamp model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new SpecialChamp();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing SpecialChamp model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing SpecialChamp model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$championship = $this->findModel($id);
		$stages = $championship->stages;
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($stages as $stage) {
			if ($stage->photoPath) {
				HelpModel::deleteFile($stage->photoPath);
			}
			if (!$stage->delete()) {
				$transaction->rollBack();
				
				return 'Возникла ошибка. Обратитесь к разработчику.';
			}
		}
		$championship->delete();
		$transaction->commit();
		
		return true;
	}
	
	/**
	 * Finds the SpecialChamp model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return SpecialChamp the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = SpecialChamp::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionCreateStage($championshipId)
	{
		$championship = $this->findModel($championshipId);
		$stage = new SpecialStage();
		$stage->championshipId = $championship->id;
		if ($stage->load(Yii::$app->request->post()) && $stage->save()) {
			return $this->redirect(['view-stage', 'id' => $stage->id]);
		}
		
		return $this->render('create-stage', [
			'championship' => $championship,
			'stage'        => $stage
		]);
	}
	
	public function actionViewStage($id)
	{
		$stage = SpecialStage::findOne($id);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		
		return $this->render('view-stage', ['stage' => $stage]);
	}
	
	public function actionUpdateStage($id)
	{
		$stage = SpecialStage::findOne($id);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if ($stage->load(Yii::$app->request->post()) && $stage->save()) {
			return $this->redirect(['view-stage', 'id' => $stage->id]);
		}
		
		return $this->render('update-stage', ['stage' => $stage]);
	}
	
	public function actionDeleteStage($id)
	{
		$stage = SpecialStage::findOne($id);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if ($stage->photoPath) {
			HelpModel::deleteFile($stage->photoPath);
		}
		$stage->delete();
		
		return true;
	}
	
	public function actionParticipants($stageId)
	{
		$stage = SpecialStage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		$searchModel = new RequestForSpecialStageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['stageId' => $stage->id]);
		
		$formModel = new ParticipantForm();
		$formModel->stageId = $stage->id;
		if ($formModel->load(\Yii::$app->request->post()) && $formModel->save()) {
			return $this->redirect(['participants', 'stageId' => $stage->id]);
		}
		
		$forSearch = RequestForSpecialStage::find()->from(['a' => RequestForSpecialStage::tableName(), 'b' => Athlete::tableName()])
			->select(['a."athleteId"', '(b."lastName" || \' \' || b."firstName") as "name"'])
			->where(new Expression('"a"."athleteId" = "b"."id"'))
			->andWhere(['stageId' => $stageId])
			->orderBy(['name' => SORT_ASC])
			->distinct()
			->asArray()->all();
		if ($forSearch) {
			$forSearch = ArrayHelper::map($forSearch, 'athleteId', 'name');
		}
		
		return $this->render('participants', [
			'stage'        => $stage,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'formModel'    => $formModel,
			'forSearch'    => $forSearch
		]);
	}
	
	public function actionDeleteParticipant($id)
	{
		$participant = RequestForSpecialStage::findOne($id);
		if (!$participant) {
			throw new NotFoundHttpException('Результат не найден');
		}
		$stageId = $participant->stageId;
		$old = null;
		if ($participant->status == RequestForSpecialStage::STATUS_APPROVE) {
			/** @var RequestForSpecialStage $old */
			$old = RequestForSpecialStage::find()->where(['not', ['id' => $participant->id]])
				->andWhere(['athleteId' => $participant->athleteId, 'stageId' => $participant->stageId])
				->andWhere(['status' => RequestForSpecialStage::STATUS_IN_ACTIVE])
				->orderBy(['resultTime' => SORT_ASC])
				->one();
		}
		$participant->delete();
		if ($old) {
			$old->status = RequestForSpecialStage::STATUS_APPROVE;
			$old->save();
		}
		
		return $this->redirect(['participants', 'stageId' => $stageId]);
	}
	
	public function actionUpdateParticipant($id)
	{
		$participant = RequestForSpecialStage::findOne($id);
		if (!$participant) {
			throw new NotFoundHttpException('Результат не найден');
		}
		
		if ($participant->load(\Yii::$app->request->post()) && $participant->save()) {
			
			return $this->redirect(['participants', 'stageId' => $participant->stageId]);
		}
		
		return $this->render('update-participant', [
			'participant' => $participant
		]);
	}
	
	public function actionRegistrations()
	{
		/** @var RequestForSpecialStage[] $requests */
		$requests = RequestForSpecialStage::find()->where(['status' => RequestForSpecialStage::STATUS_NEED_CHECK])
			->orderBy(['dateAdded' => SORT_ASC])->all();
		$result = [];
		foreach ($requests as $request) {
			$item = [
				'request'      => $request,
				'coincidences' => []
			];
			if (!$request->athleteId) {
				$item['coincidences'] = $request->getCoincidences();
			}
			$result[] = $item;
		}
		
		return $this->render('registrations', ['result' => $result]);
	}
	
	public function actionApprove($id)
	{
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Заявка не найдена';
		}
		
		if (\Yii::$app->mutex->acquire('SpecialStageRequests-' . $request->id, 10)) {
			$request = RequestForSpecialStage::findOne($id);
			if ($request->status !== RequestForSpecialStage::STATUS_NEED_CHECK) {
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Запись уже была обработана. Пожалуйста, перезагрузите страницу.';
			}
			if ($request->athleteId) {
				$request->status = RequestForSpecialStage::STATUS_APPROVE;
				if (!$request->save()) {
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return 'Возникла ошибка при сохранении';
				}
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return true;
			} else {
				$data = $request->getData();
				if ($data['cityId']) {
					$city = City::findOne($data['cityId']);
					if (!$city) {
						\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
						
						return 'Город не найден';
					}
				} else {
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return 'Необходимо выбрать город из списка';
				}
				
				$transaction = \Yii::$app->db->beginTransaction();
				$athlete = new Athlete();
				$athlete->lastName = $data['lastName'];
				$athlete->firstName = $data['firstName'];
				$athlete->cityId = $city->id;
				$athlete->countryId = $city->countryId;
				$athlete->regionId = $city->regionId;
				
				if (!Athlete::find()->where(['upper("email")' => mb_strtoupper($data['email'], 'UTF-8')])->one()) {
					$athlete->email = $data['email'];
				}
				if (!$athlete->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return var_dump($athlete->errors);
				}
				
				$motorcycle = new Motorcycle();
				$motorcycle->athleteId = $athlete->id;
				$motorcycle->mark = $data['motorcycleMark'];
				$motorcycle->model = $data['motorcycleModel'];
				$motorcycle->cbm = $data['cbm'];
				$motorcycle->power = $data['power'];
				$motorcycle->isCruiser = $data['isCruiser'] ?? 0;
				if (!$motorcycle->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return var_dump($motorcycle->errors);
				}
				
				$request->athleteId = $athlete->id;
				$request->motorcycleId = $motorcycle->id;
				$request->status = RequestForSpecialStage::STATUS_APPROVE;
				if (!$request->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return 'Возникла ошибка при сохранении';
				}
				
				if ($athlete->hasAccount) {
					$text = 'Результат ' . $request->resultTimeHuman . ' для этапа ' . $request->stage->title . ' подтверждён';
					Notice::add($request->athleteId, $text);
				}
				
				$transaction->commit();
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return true;
			}
		}
		\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
		
		return true;
	}
	
	public function actionCancel()
	{
		$id = \Yii::$app->request->post('id');
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Заявка не найдена';
		}
		$text = trim(\Yii::$app->request->post('reason'));
		if (!$text) {
			return 'Укажите причину отказа!';
		}
		
		if (\Yii::$app->mutex->acquire('SpecialStageRequests-' . $request->id, 10)) {
			$request = RequestForSpecialStage::findOne($id);
			if ($request->status !== RequestForSpecialStage::STATUS_NEED_CHECK) {
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Запись уже была обработана. Пожалуйста, перезагрузите страницу.';
			}
			$email = null;
			if ($request->athleteId) {
				$athlete = $request->athlete;
				if ($athlete->hasAccount) {
					$sendText = 'Результат отклонён';
					$link = null;
					if ((mb_strlen($text, 'UTF-8') + mb_strlen($sendText, 'UTF-8')) < 253) {
						$sendText .= ': ' . $text;
					} else {
						$sendText = 'Результат ' . $request->resultTimeHuman . ' для этапа "'
							. $request->stage->title . '" отклонён. Подробности по ссылке';
						$link = '/competitions/special-stages-history';
					}
					Notice::add($request->athleteId, $sendText, $link);
				}
				$email = $athlete->email;
			} else {
				$data = $request->getData();
				$email = $data['email'];
			}
			
			if (YII_ENV == 'prod') {
				$sendText = 'Ваш результат для этапа "' . $request->stage->title . '" чемпионата "' . $request->stage->championship->title . '"' .
					' отклонён.<br>';
				$sendText .= $text;
				if (mb_stripos($email, '@', null, 'UTF-8')) {
					\Yii::$app->mailer->compose('@common/mail/text', ['text' => $sendText])
						->setTo($email)
						->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
						->setSubject('gymkhana-cup: ваш результат отклонён')
						->send();
				}
			}
			
			$request->status = RequestForSpecialStage::STATUS_CANCEL;
			$request->cancelReason = $text;
			if (!$request->save()) {
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Возникла ошибка при сохранении';
			}
			\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
			
			return true;
		}
		\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
		
		return true;
	}
	
	public function actionApproveForAthlete($id, $athleteId)
	{
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Заявка не найдена';
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		if (\Yii::$app->mutex->acquire('SpecialStageRequests-' . $request->id, 10)) {
			$request = RequestForSpecialStage::findOne($id);
			if ($request->status !== RequestForSpecialStage::STATUS_NEED_CHECK) {
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Запись уже была обработана. Пожалуйста, перезагрузите страницу.';
			}
			
			$data = $request->getData();
			
			$transaction = \Yii::$app->db->beginTransaction();
			
			if (!$athlete->email && !Athlete::find()->where(['upper("email")' => mb_strtoupper($data['email'], 'UTF-8')])->one()) {
				$athlete->email = $data['email'];
				if (!$athlete->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return var_dump($athlete->errors);
				}
			}
			
			$motorcycle = new Motorcycle();
			$motorcycle->athleteId = $athlete->id;
			$motorcycle->mark = $data['motorcycleMark'];
			$motorcycle->model = $data['motorcycleModel'];
			$motorcycle->cbm = $data['cbm'];
			$motorcycle->power = $data['power'];
			$motorcycle->isCruiser = $data['isCruiser'] ?? 0;
			if (!$motorcycle->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return var_dump($motorcycle->errors);
			}
			
			$request->athleteId = $athlete->id;
			$request->motorcycleId = $motorcycle->id;
			$request->status = RequestForSpecialStage::STATUS_APPROVE;
			if (!$request->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Возникла ошибка при сохранении';
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
			
			return true;
		}
		\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
		
		return true;
	}
	
	public function actionApproveForAthleteOnMotorcycle($id, $athleteId, $motorcycleId)
	{
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Заявка не найдена';
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		$motorcycle = Motorcycle::findOne($motorcycleId);
		if (!$motorcycle || $motorcycle->athleteId != $athlete->id) {
			return 'Мотоцикл не найден';
		}
		
		if (\Yii::$app->mutex->acquire('SpecialStageRequests-' . $request->id, 10)) {
			$request = RequestForSpecialStage::findOne($id);
			if ($request->status !== RequestForSpecialStage::STATUS_NEED_CHECK) {
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Запись уже была обработана. Пожалуйста, перезагрузите страницу.';
			}
			
			$data = $request->getData();
			
			$transaction = \Yii::$app->db->beginTransaction();
			
			if (!$athlete->email && !Athlete::find()->where(['upper("email")' => mb_strtoupper($data['email'], 'UTF-8')])->one()) {
				$athlete->email = $data['email'];
				if (!$athlete->save()) {
					$transaction->rollBack();
					\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
					
					return var_dump($athlete->errors);
				}
			}
			
			$request->athleteId = $athlete->id;
			$request->motorcycleId = $motorcycle->id;
			$request->status = RequestForSpecialStage::STATUS_APPROVE;
			if (!$request->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
				
				return 'Возникла ошибка при сохранении';
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
			
			return true;
		}
		\Yii::$app->mutex->release('SpecialStageRequests-' . $request->id);
		
		return true;
	}
	
	public function actionSaveNewCity()
	{
		$id = \Yii::$app->request->post('id');
		$city = \Yii::$app->request->post('city');
		if (!$id || !$city) {
			return 'Неверные данные';
		}
		
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Запрос не найден';
		}
		if ($request->cityId) {
			return 'Спортсмену уже установлен город';
		}
		
		$city = City::findOne(['countryId' => $request->countryId, 'id' => $city]);
		if (!$city) {
			return 'Город не найден';
		}
		
		$request->cityId = $city->id;
		if (!$request->save()) {
			return var_dump($request);
		}
		
		return true;
	}
	
	public function actionCalculationResult($stageId)
	{
		$stage = SpecialStage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!$stage->classCalculate()) {
			throw new UserException('Не удалось рассчитать класс соревнования');
		}
		$result = $stage->placesCalculate();
		if ($result !== true) {
			throw new UserException($result);
		}
		
		return $this->redirect(['stage-results', 'stageId' => $stageId]);
	}
	
	public function actionStageResults($stageId)
	{
		$stage = SpecialStage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		$requests = $stage->activeRequests;
		
		return $this->render('stage-result', [
			'stage'    => $stage,
			'requests' => $requests
		]);
	}
	
	public function actionApproveClass($id)
	{
		$this->can('competitions');
		
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Участник не найден';
		}
		$request->needUpdatePlaces = false;
		$result = $this->approveClassForParticipant($request);
		if ($result !== true) {
			return $result;
		}
		
		return true;
	}
	
	public function approveClassForParticipant(RequestForSpecialStage $request)
	{
		$this->can('competitions');
		
		if ($request->newAthleteClassStatus != RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$athlete = $request->athlete;
		
		if ($athlete->athleteClass->percent <= $request->newAthleteClass->percent) {
			$request->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_APPROVE;
			if (!$request->save()) {
				
				return 'Невозможно сохранить изменения для участника. Свяжитесь с разработчиком.';
			}
			
			return true;
		}
		
		if ($athlete->athleteClassId != $request->newAthleteClassId) {
			$transaction = \Yii::$app->db->beginTransaction();
			
			$event = $request->stage->championship->title . ', ' . $request->stage->title;
			$history = ClassHistory::create($athlete->id, $request->motorcycleId,
				$athlete->athleteClassId, $request->newAthleteClassId, $event,
				$request->resultTime, $request->stage->referenceTime, $request->percent);
			if (!$history) {
				$transaction->rollBack();
				
				return 'Возникла ошибка при изменении данных. Свяжитесь с разработчиком.';
			}
			
			$athlete->athleteClassId = $request->newAthleteClassId;
			if (!$athlete->save()) {
				$transaction->rollBack();
				
				return 'Невозможно изменить класс спортсмену ' . $athlete->getFullName() . '. Свяжитесь с разработчиком.';
			}
			
			$request->newAthleteClassStatus = RequestForSpecialStage::NEW_CLASS_STATUS_APPROVE;
			if (!$request->save()) {
				$transaction->rollBack();
				
				return 'Невозможно сохранить изменения для участника. Свяжитесь с разработчиком.';
			}
			$transaction->commit();
		}
		
		return true;
	}
	
	public function actionCancelClass($id)
	{
		$this->can('competitions');
		
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Участник не найден';
		}
		$request->needUpdatePlaces = false;
		if ($request->newAthleteClassStatus != RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$request->newAthleteClassId = null;
		$request->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_CANCEL;
		if (!$request->save()) {
			return var_dump($request->errors);
		}
		
		return true;
	}
	
	public function actionApproveAllClasses($id)
	{
		$this->can('competitions');
		
		$stage = SpecialStage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		
		/** @var RequestForSpecialStage[] $requests */
		$requests = $stage->getActiveRequests()->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => Participant::NEW_CLASS_STATUS_NEED_CHECK])->all();
		$errors = null;
		foreach ($requests as $request) {
			$request->needUpdatePlaces = false;
			$result = $this->approveClassForParticipant($request);
			if ($result !== true) {
				$errors .= $result . PHP_EOL . PHP_EOL;
			}
		}
		if ($errors) {
			return $errors;
		}
		
		return true;
	}
	
	/**
	 * @param $id
	 *
	 * @return bool|string
	 * @throws \yii\web\ForbiddenHttpException
	 */
	public function actionCancelAllClasses($id)
	{
		$this->can('competitions');
		
		$stage = SpecialStage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		
		/** @var RequestForSpecialStage[] $requests */
		$requests = $stage->getActiveRequests()->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK])->all();
		foreach ($requests as $request) {
			$request->needUpdatePlaces = false;
			if ($request->newAthleteClassStatus != RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK) {
				return 'Запись уже была обработана';
			}
			$request->newAthleteClassId = null;
			$request->newAthleteClassStatus = RequestForSpecialStage::NEW_CLASS_STATUS_CANCEL;
			if (!$request->save()) {
				return var_dump($request->errors);
			}
		}
		
		return true;
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionResults($id)
	{
		$championship = $this->findModel($id);
		$results = $championship->getResults();
		$stages = $championship->stages;
		$outOfChampStages = $championship->getStages()->andWhere(['outOfCompetitions' => 1])->all();
		
		return $this->render('results', [
			'championship'     => $championship,
			'results'          => $results,
			'stages'           => $stages,
			'outOfChampStages' => $outOfChampStages
		]);
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws \yii\web\ForbiddenHttpException
	 */
	public function actionFindTmpAthlete($id)
	{
		$this->can('competitions');
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Ошибка!';
		}
		$model = SpecialRequestForm::set($request);
		
		return $this->renderAjax('_tmp_athlete', ['formModel' => $model, 'id' => $id]);
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 * @throws \yii\web\ForbiddenHttpException
	 */
	public function actionUpdateInfo($id)
	{
		$this->can('competitions');
		$request = RequestForSpecialStage::findOne($id);
		if (!$request) {
			return 'Ошибка!';
		}
		$model = new SpecialRequestForm();
		if ($model->load(\Yii::$app->request->post())) {
			if ($request->athleteId) {
				$request->timeHuman = $model->timeHuman;
				$request->dateHuman = $model->dateHuman;
				$request->fine = $model->fine;
				if ($request->save()) {
					return true;
				}
			} else {
				if (!$model->cityId) {
					return 'Необходимо выбрать город';
				}
				$data = $request->getData();
				foreach ($model->attributeLabels() as $attr => $label) {
					$data[$attr] = $model->$attr;
				}
				$request->data = json_encode($data);
				$request->timeHuman = $model->timeHuman;
				$request->dateHuman = $model->dateHuman;
				$request->fine = $model->fine;
				$request->cityId = $model->cityId;
				$request->countryId = $model->countryId;
				if ($request->save()) {
					return true;
				}
			}
		}
		
		return 'Ошибка! Напишите Наде';
	}
}
