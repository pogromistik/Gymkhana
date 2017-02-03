<?php

namespace admin\controllers\competitions;

use common\models\Athlete;
use common\models\Stage;
use common\models\Time;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\Participant;
use common\models\search\ParticipantSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParticipantsController implements the CRUD actions for Participant model.
 */
class ParticipantsController extends Controller
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
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		
		$searchModel = new ParticipantSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['stageId' => $stageId]);
		
		$participant = new Participant();
		$participant->stageId = $stage->id;
		$participant->championshipId = $stage->championshipId;
		if ($participant->load(Yii::$app->request->post()) && $participant->save()) {
			return $this->redirect(['index', 'stageId' => $stageId]);
		}
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'stage'        => $stage,
			'participant'  => $participant
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
		if (($model = Participant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionMotorcycleCategory()
	{
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
		$athlete = Athlete::findOne($athleteId);
		$motorcycles = $athlete->motorcycles;
		$result = [];
		foreach ($motorcycles as $motorcycle) {
			$result[] = ['id' => $motorcycle->id, 'name' => $motorcycle->model . ' ' . $motorcycle->mark];
		}
		
		return $result;
	}
	
	public function actionRaces($stageId)
	{
		$stage = Stage::findOne($stageId);
		
		return $this->render('races', [
			'stage' => $stage
		]);
	}
	
	public function actionAddTime()
	{
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
}
