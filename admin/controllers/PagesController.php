<?php

namespace admin\controllers;

use Yii;
use common\models\Page;
use common\models\search\PageSearch;
use yii\web\NotFoundHttpException;

/**
 * PagesController implements the CRUD actions for Page model.
 */
class PagesController extends BaseController
{
	/**
	 * Lists all Page models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('developer');
		
		$searchModel = new PageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new Page model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('developer');
		
		$model = new Page();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Page model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$this->can('developer');
		
		$model = $this->findModel($id);
		
		$success = null;
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$success = 'Изменения успешно сохранены';
		}
		
		return $this->render('update', [
			'model'   => $model,
			'success' => $success
		]);
	}
	
	/**
	 * Finds the Page model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Page the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Page::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionAjaxUpdate()
	{
		$this->can('admin');
		
		$params = \Yii::$app->request->getBodyParams();
		$id = $params['Page']['id'] ? $params['Page']['id'] : null;
		if ($id) {
			$page = Page::findOne($id);
			if (!$page) {
				return 'Страница не найдена';
			}
		} else {
			$page = new Page();
		}
		if ($page->load(\Yii::$app->request->post()) && $page->save()) {
			return true;
		}
		
		return 'Возникла ошибка при сохранении данных';
	}
}
