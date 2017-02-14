<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\Motorcycle;
use common\models\Stage;
use common\models\Time;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Participant;
use common\models\search\ParticipantSearch;
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
			'stageId' => $participant->stageId]);
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
		
		return $this->render('races', [
			'stage' => $stage
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
}
