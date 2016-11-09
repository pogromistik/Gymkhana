<?php

namespace admin\controllers;

use common\models\HelpModel;
use Yii;
use common\models\Album;
use common\models\search\AlbumSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlbumController implements the CRUD actions for Album model.
 */
class AlbumController extends BaseController
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
	 * Lists all Album models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('admin');

		$searchModel = new AlbumSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Album model.
	 *
	 * @param integer $id
	 *
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
	 * Creates a new Album model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Album();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->folder = 'albums/' . $model->year->year . '/' . $model->id;
			$model->save(false);
			HelpModel::createFolder('albums/' . $model->year->year);
			HelpModel::createFolder($model->folder);
			
			HelpModel::saveOtherPhoto($model, $model->folder . '/cover', 'cover', 'coverFile', true);

			return $this->redirect(['update', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Album model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$this->can('admin');

		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			HelpModel::saveOtherPhoto($model, 'albums/' . $model->year->year, 'cover', 'coverFile');
			
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Album model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->can('admin');
		$model = $this->findModel($id);
		if ($model->cover) {
			$covers = $model->getCovers();
			foreach ($covers as $cover) {
				HelpModel::deleteFile($model->folder . '/cover/' . $cover);
			}
		}

		$photos = $model->getPhotos();
		foreach ($photos as $photo) {
			HelpModel::deleteFile($model->folder . '/' . $photo);
		}

		$model->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Album model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Album the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Album::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionDeletePhoto($photoId)
	{
		return HelpModel::deleteFile($photoId);
	}
}
