<?php

namespace backend\controllers;

use common\models\HelpModel;
use common\models\VideoType;
use Yii;
use common\models\Video;
use common\models\search\VideoSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VideoController implements the CRUD actions for Video model.
 */
class VideoController extends BaseController
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
	 * Lists all Video models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('admin');
		
		$searchModel = new VideoSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$types = VideoType::getActive();

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'types'        => $types
		]);
	}
	
	public function actionVideoType($typeId = null)
	{
		$this->can('admin');
		
		if ($typeId) {
			$type = VideoType::findOne($typeId);
			if (!$type) {
				throw new NotFoundHttpException('Раздел видео не найден');
			}
		} else {
			$type = new VideoType();
		}
		
		if ($type->load(Yii::$app->request->post())) {
			if ($type->save()) {
				HelpModel::saveOtherPhoto($type, 'video', 'picture', 'pictureFile');
				return $this->redirect(['index']);
			} else {
				return var_dump($type->errors);
			}
		}
		
		return $this->render('video-type', ['type' => $type]);
	}

	/**
	 * Displays a single Video model.
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
	 * Creates a new Video model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('admin');
		
		$model = new Video();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Video model.
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
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Finds the Video model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Video the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Video::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
