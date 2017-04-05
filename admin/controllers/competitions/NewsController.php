<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use Yii;
use common\models\AssocNews;
use common\models\search\AssocNewsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for AssocNews model.
 */
class NewsController extends BaseController
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
	 * Lists all AssocNews models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('competitions');
		
		$searchModel = new AssocNewsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new AssocNews model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('competitions');
		
		$model = new AssocNews();
		$model->datePublishHuman = date('d.m.Y', time());
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing AssocNews model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @param bool    $success
	 * @return mixed
	 */
	public function actionUpdate($id, $success = false)
	{
		$this->can('competitions');
		
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('update', [
				'model'   => $model,
				'success' => $success
			]);
		}
	}
	
	/**
	 * Deletes an existing AssocNews model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->can('competitions');
		
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the AssocNews model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return AssocNews the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = AssocNews::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
