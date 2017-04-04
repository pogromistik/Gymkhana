<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use Yii;
use common\models\Point;
use common\models\search\PointSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdditionalController implements the CRUD actions for Point model.
 */
class AdditionalController extends BaseController
{
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
	
	/**
	 * Lists all Point models.
	 *
	 * @return mixed
	 */
	public function actionPoints()
	{
		$this->can('globalWorkWithCompetitions');
		
		$searchModel = new PointSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('points', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new Point model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreatePoints()
	{
		$this->can('globalWorkWithCompetitions');
		
		$model = new Point();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['points', 'id' => $model->id]);
		} else {
			return $this->render('create-points', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Point model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdatePoints($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['points']);
		} else {
			return $this->render('update-points', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing Point model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDeletePoints($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		$this->findModel($id)->delete();
		
		return $this->redirect(['points']);
	}
	
	/**
	 * Finds the Point model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Point the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('globalWorkWithCompetitions');
		
		if (($model = Point::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
