<?php

namespace admin\controllers;

use common\models\Files;
use common\models\HelpModel;
use common\models\Page;
use Yii;
use common\models\Track;
use common\models\search\TrackSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TracksController implements the CRUD actions for Track model.
 */
class TracksController extends BaseController
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
		$page = Page::findOne(['layoutId' => 'tracks']);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		
		$searchModel = new TrackSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'page'         => $page
		]);
	}
	
	public function actionCreateOrUpdate($id = null, $success = null)
	{
		$this->can('admin');
		$model = new Track();
		if ($id) {
			$model = $this->findModel($id);
		}
		
		$file = new Files();
		$documentId = null;
		if ($file->load(Yii::$app->request->post())) {
			$documentId = $file->saveFile(Files::TYPE_DOCUMENTS, true);
			if ($documentId === 'error saveAs') {
				throw new Exception('Возникла ошибка при сохранении файла. Проверьте директиву upload_max_filesize');
			}
		}
		if ($model->load(Yii::$app->request->post())) {
			if ($documentId) {
				$model->documentId = $documentId;
			}
			if (!$model->save()) {
				return var_dump($model);
			}
			HelpModel::saveOtherPhoto($model, 'tracks/', 'photoPath', 'photoFile', true);
			
			return $this->redirect(['create-or-update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create-or-update', [
				'model'   => $model,
				'success' => $success
			]);
		}
	}
	
	/**
	 * Deletes an existing Track model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->can('admin');
		$model = $this->findModel($id);
		if ($model->photoPath) {
			HelpModel::deleteFile($model->photoPath);
		}
		if ($model->documentId) {
			HelpModel::deleteFile($model->document->folder);
		}
		$model->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Track model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Track the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Track::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
