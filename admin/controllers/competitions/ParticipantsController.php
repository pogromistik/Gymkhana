<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Motorcycle;
use common\models\Stage;
use common\models\Time;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Participant;
use common\models\search\ParticipantSearch;
use yii\base\UserException;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
		
		$error = null;
		
		$searchModel = new ParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['stageId' => $stageId]);
		
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
			}
			if (!$error && $participant->save()) {
				return $this->redirect(['index', 'stageId' => $stageId]);
			}
		}
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'stage'        => $stage,
			'participant'  => $participant,
			'error'        => $error
		]);
	}
	
	public function actionSort($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		$query = new Query();
		$query->from(['a' => Participant::tableName(), 'b' => Athlete::tableName(), 'c' => Motorcycle::tableName()]);
		$query->where(['a.stageId' => $stageId]);
		$query->select(['a."id", b."lastName", b."firstName", c."mark", c."model", a."number"']);
		$query->andWhere(['a.status' => Participant::STATUS_ACTIVE]);
		$query->andWhere(new Expression('"a"."athleteId" = "b"."id"'));
		$query->andWhere(new Expression('"a"."motorcycleId" = "c"."id"'));
		$query->orderBy(['a.sort' => SORT_ASC, 'a.id' => SORT_ASC]);
		$participants = $query->all();
		$participantsArray = [];
		foreach ($participants as $participant) {
			$content = $participant['lastName'] . ' ' . $participant['firstName'];
			if (isset($participant['number'])) {
				$content .= ', №' . $participant['number'];
			}
			$content .= ', ' . $participant['model'] . ' ' . $participant['mark'];
			$participantsArray[$participant['id']] = ['content' => $content];
		}
		
		return $this->render('sort', ['participantsArray' => $participantsArray, 'stage' => $stage]);
	}
	
	public function actionChangeSort()
	{
		$sortList = \Yii::$app->request->getBodyParam('sort_list');
		$sortItems = explode(',', $sortList);
		$i = 1;
		$values = '';
		foreach ($sortItems as $item) {
			$values .= '(' . $item . ',' . $i++ . ')';
		}
		$transaction = \Yii::$app->db->beginTransaction();
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
	
	public function getSubCatList($athleteId)
	{
		$this->can('competitions');
		
		$athlete = Athlete::findOne($athleteId);
		$motorcycles = $athlete->getMotorcycles()->andWhere(['status' => Motorcycle::STATUS_ACTIVE])->all();
		$result = [];
		foreach ($motorcycles as $motorcycle) {
			$result[] = ['id' => $motorcycle->id, 'name' => $motorcycle->model . ' ' . $motorcycle->mark];
		}
		
		return $result;
	}
	
	public function actionRaces($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		$error = false;
		
		if (Participant::find()->where(['stageId' => $stage->id])->andWhere(['athleteClassId' => null])->one()) {
			$error = 'Не установлены классы спортсменов';
		}
		
		return $this->render('races', [
			'stage' => $stage,
			'error' => $error
		]);
	}
	
	public function actionAddTime()
	{
		$this->can('competitions');
		
		$params = \Yii::$app->request->getBodyParams();
		if (isset($params['Time']['id'])) {
			$time = Time::findOne($params['Time']['id']);
		} else {
			$time = new Time();
		}
		$time->load(\Yii::$app->request->post());
		if ($time->load(\Yii::$app->request->post())) {
			trim($time->timeForHuman, '_');
			if ($time->save()) {
				return true;
			}
			
			return var_dump($time->errors);
		}
		
		return false;
	}
	
	public function actionChangeStatus($id)
	{
		$this->can('competitions');
		
		$participant = Participant::findOne($id);
		if (!$participant) {
			return 'Заявка не найдена';
		}
		
		if ($participant->status == Participant::STATUS_ACTIVE) {
			$stage = $participant->stage;
			if ($stage->status == Stage::STATUS_PRESENT || $stage->status == Stage::STATUS_CALCULATE_RESULTS) {
				$participant->status = Participant::STATUS_DISQUALIFICATION;
			} else {
				$participant->status = Participant::STATUS_CANCEL_ADMINISTRATION;
			}
		} else {
			$participant->status = Participant::STATUS_ACTIVE;
		}
		
		if ($participant->save()) {
			return true;
		}
		
		return 'Возникла ошибка при сохранении изменений';
	}
	
	public function actionSetClasses($stageId)
	{
		$this->can('competitions');
		
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			return 'Этап не найден';
		}
		
		$participants = Participant::findAll(['stageId' => $stageId, 'status' => Participant::STATUS_ACTIVE]);
		foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			if (!$athlete->athleteClassId) {
				return 'Необходимо сначала установить класс для спортсмена ' . $athlete->getFullName();
			}
			$participant->athleteClassId = $athlete->athleteClassId;
			if (!$participant->save()) {
				return 'Не удалось установить класс участнику ' . $athlete->getFullName();
			}
		}
		
		$classIds = Participant::find()->select('athleteClassId')
			->where(['stageId' => $stageId, 'status' => Participant::STATUS_ACTIVE])->distinct()->asArray()->column();
		$percent = AthletesClass::find()->select('id')->where(['id' => $classIds])->min('"percent"');
		$class = AthletesClass::findOne(['percent' => $percent, 'id' => $classIds]);
		$stage->class = $class->id;
		if (!$stage->save()) {
			return 'Не удалось установить класс соревнований';
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
		
		$athlete = $participant->athlete;
		if ($athlete->athleteClass->percent < $participant->newAthleteClass->percent) {
			return 'Вы пытаетесь понизить спортсмену класс с ' . $athlete->athleteClass->title . ' на '
				. $participant->newAthleteClass->title . '. Понижение класса невозможно';
		}
		
		$athlete->athleteClassId = $participant->newAthleteClassId;
	}
}
