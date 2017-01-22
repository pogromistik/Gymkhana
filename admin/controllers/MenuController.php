<?php

namespace admin\controllers;

use common\models\GroupMenu;
use common\models\search\GroupMenuSearch;
use Yii;
use common\models\MenuItem;
use common\models\search\MenuItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for MenuItem model.
 */
class MenuController extends BaseController
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
	 * Lists all MenuItem models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('admin');
		$searchModel = new MenuItemSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$searchModelGroup = new GroupMenuSearch();
		$dataProviderGroup = $searchModelGroup->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'       => $searchModel,
			'dataProvider'      => $dataProvider,
			'searchModelGroup'  => $searchModelGroup,
			'dataProviderGroup' => $dataProviderGroup,
		]);
	}
	
	/**
	 * Creates a new MenuItem model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('admin');
		$model = new MenuItem();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	public function actionChangeGroup($id = null, $success = false)
	{
		$this->can('admin');
		if ($id) {
			$model = GroupMenu::findOne($id);
			if (!$model) {
				throw new NotFoundHttpException();
			}
		} else {
			$model = new GroupMenu();
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['change-group', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('change-group', [
				'model'   => $model,
				'success' => $success
			]);
		}
	}
	
	/**
	 * Updates an existing MenuItem model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @param         $success
	 * @return mixed
	 */
	public function actionUpdate($id, $success = false)
	{
		$this->can('admin');
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
	 * Deletes an existing MenuItem model.
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
	
	public function actionDeleteGroup($id)
	{
		$this->can('admin');
		$model = GroupMenu::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException();
		}
		MenuItem::deleteAll(['groupsMenuId' => $model->id]);
		$model->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the MenuItem model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return MenuItem the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = MenuItem::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
