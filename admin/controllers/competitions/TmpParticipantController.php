<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\City;
use common\models\Motorcycle;
use common\models\Participant;
use common\models\Stage;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\TmpParticipant;
use common\models\search\TmpParticipantSearch;
use yii\web\NotFoundHttpException;

/**
 * TmpParticipantController implements the CRUD actions for TmpParticipant model.
 */
class TmpParticipantController extends BaseController
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => TmpParticipant::className(),
				'forceCreate' => false
			]
		];
	}
	
	/**
	 * Lists all TmpParticipant models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('competitions');
		
		$searchModel = new TmpParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['status' => TmpParticipant::STATUS_NEW]);
		
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			$stageIds = Stage::find()->select('id')->where(['regionId' => \Yii::$app->user->identity->regionId])
				->andWhere(['not', ['status' => Stage::STATUS_PAST]])->asArray()->column();
			$dataProvider->query->andWhere(['stageId' => $stageIds]);
		}
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single TmpParticipant model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	/*public function actionView($id)
	{
		$this->can('competitions');
		
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	*/
	/**
	 * Finds the TmpParticipant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return TmpParticipant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('competitions');
		
		if (($model = TmpParticipant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionFindAthletes($lastName)
	{
		$this->can('competitions');
		
		$lastName = mb_strtoupper($lastName, 'UTF-8');
		$athletes = Athlete::find()->where(['upper("lastName")' => $lastName])->orWhere(['upper("lastName")' => $lastName])->all();
		
		return $this->renderAjax('_athletes', ['athletes' => $athletes, 'lastName' => $lastName]);
	}
	
	public function actionAddAndRegistration($id)
	{
		$this->can('competitions');
		
		$tmpParticipant = TmpParticipant::findOne($id);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$stage = $tmpParticipant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещён';
			}
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		if ($tmpParticipant->cityId) {
			$city = City::findOne($tmpParticipant->cityId);
			if (!$city) {
				$transaction->rollBack();
				
				return 'Город не найден';
			}
		} else {
			return 'Необходимо выбрать город из списка';
		}
		
		$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		if (\Yii::$app->mutex->acquire('TmpParticipants-' . $tmpParticipant->id, 10)) {
			$athlete = new Athlete();
			$athlete->lastName = $tmpParticipant->lastName;
			$athlete->firstName = $tmpParticipant->firstName;
			$athlete->cityId = $city->id;
			$athlete->countryId = $city->countryId;
			$athlete->regionId = $city->regionId;
			$athlete->phone = $tmpParticipant->phone;
			if ($tmpParticipant->email && !Athlete::findOne(['upper("email")' => mb_strtoupper($tmpParticipant->email, 'UTF-8')])) {
				$athlete->email = $tmpParticipant->email;
			}
			if (!$athlete->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($athlete->errors);
			}
			
			$motorcycle = new Motorcycle();
			$motorcycle->athleteId = $athlete->id;
			$motorcycle->mark = $tmpParticipant->motorcycleMark;
			$motorcycle->model = $tmpParticipant->motorcycleModel;
			if (!$motorcycle->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($motorcycle->errors);
			}
			
			$participant = new Participant();
			$participant->athleteId = $athlete->id;
			$participant->motorcycleId = $motorcycle->id;
			if ($tmpParticipant->number) {
				$participant->number = $tmpParticipant->number;
			}
			$participant->stageId = $tmpParticipant->stageId;
			$participant->championshipId = $tmpParticipant->championshipId;
			if (!$participant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($participant->errors);
			}
			
			$tmpParticipant->athleteId = $athlete->id;
			if (!$tmpParticipant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($tmpParticipant->errors);
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
		} else {
			$transaction->rollBack();
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionCancel($id)
	{
		$this->can('competitions');
		
		$tmpParticipant = TmpParticipant::findOne($id);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$stage = $tmpParticipant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещён';
			}
		}
		
		if (\Yii::$app->mutex->acquire('TmpParticipants-' . $tmpParticipant->id, 10)) {
			$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
			if (!$tmpParticipant->save()) {
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($tmpParticipant->errors);
			}
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
		} else {
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionRegistration($tmpParticipantId, $athleteId, $motorcycleId)
	{
		$this->can('competitions');
		
		$tmpParticipant = TmpParticipant::findOne($tmpParticipantId);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$stage = $tmpParticipant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещён';
			}
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		$motorcycle = Motorcycle::findOne(['id' => $motorcycleId, 'athleteId' => $athleteId]);
		if (!$motorcycle) {
			return 'Мотоцикл не найден';
		}
		
		/** @var Participant $old */
		$old = Participant::find()->where(['stageId' => $tmpParticipant->stageId])
			->andWhere(['athleteId' => $athleteId])->andWhere(['motorcycleId' => $motorcycleId])->one();
		if ($old) {
			if ($old->status == Participant::STATUS_ACTIVE) {
				return 'Спортсмен уже зарегистрирован на этап на этом мотоцикле';
			} elseif ($old->status == Participant::STATUS_DISQUALIFICATION) {
				return 'Спортсмен дисквалифицирован, регистрация невозможна';
			}
			$old->status = Participant::STATUS_ACTIVE;
			$old->save();
			
			$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
			$tmpParticipant->athleteId = $athlete->id;
			if (!$tmpParticipant->save()) {
				return var_dump($tmpParticipant->errors);
			}
			
			return true;
		}
		
		if (\Yii::$app->mutex->acquire('TmpParticipants-' . $tmpParticipant->id, 10)) {
			$transaction = \Yii::$app->db->beginTransaction();
			
			$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
			$tmpParticipant->athleteId = $athlete->id;
			if (!$tmpParticipant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($tmpParticipant->errors);
			}
			
			$participant = new Participant();
			$participant->athleteId = $athlete->id;
			$participant->motorcycleId = $motorcycle->id;
			$participant->stageId = $tmpParticipant->stageId;
			$participant->championshipId = $tmpParticipant->championshipId;
			if ($tmpParticipant->number) {
				$participant->number = $tmpParticipant->number;
			} else {
				$championship = $tmpParticipant->championship;
				if ($athlete->number && $championship->regionId && $athlete->city->regionId == $championship->regionId) {
					$participant->number = $athlete->number;
				}
			}
			if (!$participant->save()) {
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				$transaction->rollBack();
				
				return var_dump($participant->errors);
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
		} else {
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionAddMotorcycleAndRegistration($tmpParticipantId, $athleteId)
	{
		$this->can('competitions');
		
		$tmpParticipant = TmpParticipant::findOne($tmpParticipantId);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$stage = $tmpParticipant->stage;
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				return 'Доступ запрещён';
			}
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		if (\Yii::$app->mutex->acquire('TmpParticipants-' . $tmpParticipant->id, 10)) {
			$transaction = \Yii::$app->db->beginTransaction();
			
			$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
			$tmpParticipant->athleteId = $athlete->id;
			if (!$tmpParticipant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($tmpParticipant->errors);
			}
			
			$motorcycle = new Motorcycle();
			$motorcycle->athleteId = $athlete->id;
			$motorcycle->mark = $tmpParticipant->motorcycleMark;
			$motorcycle->model = $tmpParticipant->motorcycleModel;
			if (!$motorcycle->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($motorcycle->errors);
			}
			
			$participant = new Participant();
			$participant->athleteId = $athlete->id;
			$participant->motorcycleId = $motorcycle->id;
			$participant->stageId = $tmpParticipant->stageId;
			$participant->championshipId = $tmpParticipant->championshipId;
			if ($tmpParticipant->number) {
				$participant->number = $tmpParticipant->number;
			} else {
				$athlete = $participant->athlete;
				$championship = $participant->championship;
				if ($athlete->number && $championship->regionId && $athlete->city->regionId == $championship->regionId) {
					$participant->number = $athlete->number;
				}
			}
			if (!$participant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($participant->errors);
			}
			
			$tmpParticipant->athleteId = $athlete->id;
			if (!$tmpParticipant->save()) {
				$transaction->rollBack();
				\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
				
				return var_dump($tmpParticipant->errors);
			}
			
			$transaction->commit();
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
		} else {
			\Yii::$app->mutex->release('TmpParticipants-' . $tmpParticipant->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	public function actionSaveNewCity()
	{
		$this->can('competitions');
		
		$id = \Yii::$app->request->post('id');
		$city = \Yii::$app->request->post('city');
		if (!$id || !$city) {
			return 'Неверные данные';
		}
		
		$tmp = TmpParticipant::findOne($id);
		if (!$tmp) {
			return 'Спортсмен не найден';
		}
		if ($tmp->cityId) {
			return 'Спортсмену уже установлен город';
		}
		
		$city = City::findOne(['countryId' => $tmp->countryId, 'id' => $city]);
		if (!$city) {
			return 'Город не найден';
		}
		
		$tmp->cityId = $city->id;
		$tmp->city = $city->title;
		if (!$tmp->save()) {
			return var_dump($tmp);
		}
		
		return true;
	}
}
