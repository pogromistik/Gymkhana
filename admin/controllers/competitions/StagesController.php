<?php

namespace admin\controllers\competitions;

use common\models\Participant;
use Yii;
use common\models\Stage;
use common\models\search\StageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StagesController implements the CRUD actions for Stage model.
 */
class StagesController extends Controller
{
	/**
	 * Lists all Stage models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new StageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Stage model.
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
	
	public function actionCreate($championshipId, $errorCity = null, $success = null)
	{
		$model = new Stage();
		$model->championshipId = $championshipId;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model'     => $model,
				'errorCity' => $errorCity,
				'success'   => $success
			]);
		}
	}
	
	/**
	 * Updates an existing Stage model.
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
	 * Finds the Stage model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Stage the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Stage::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionResult($stageId)
	{
		$stage = $this->findModel($stageId);
		$participants = $stage->getParticipants()->orderBy(['bestTime' => SORT_ASC])->all();
		
		return $this->render('result', [
			'stage'        => $stage,
			'participants' => $participants
		]);
	}
	
	public function actionCalculationResult($id)
	{
		$stage = Stage::findOne($id);
		if (!$stage) {
			return 'Этап не найден';
		}
		
		return $stage->placesCalculate();
	}
}
