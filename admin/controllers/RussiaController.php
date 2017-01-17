<?php

namespace admin\controllers;

use common\models\Page;
use Yii;
use common\models\City;
use common\models\search\CitySearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RussiaController implements the CRUD actions for Russia model.
 */
class RussiaController extends BaseController
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
	
	public function actionIndex()
	{
		$this->can('admin');
		
		$page = Page::findOne(['layoutId' => 'russia']);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		
		$searchModel = new CitySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'page'         => $page
		]);
	}
	
	/**
	 * Displays a single Russia model.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$this->can('admin');
		
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new Russia model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('admin');
		
		$model = new City();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Russia model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$this->can('admin');
		
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
	 * Deletes an existing Russia model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->can('admin');
		
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Russia model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 
	 *
*@param integer $id
	 * @return City the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = City::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
