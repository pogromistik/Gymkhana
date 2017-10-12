<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\HelpModel;
use common\models\SpecialStage;
use common\models\Stage;
use Yii;
use common\models\SpecialChamp;
use common\models\search\SpecialChampsSearch;
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
}
