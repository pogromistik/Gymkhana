<?php

namespace admin\controllers;

use common\models\HelpModel;
use common\models\NewsBlock;
use common\models\NewsSlider;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\News;
use common\models\search\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
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

	public function actions()
	{
		return [
			'update-slider' => [
				'class'       => EditableAction::className(),
				'modelClass'  => NewsSlider::className(),
				'forceCreate' => false
			]
		];
	}

	/**
	 * Lists all News models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->can('admin');
		$searchModel = new NewsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single News model.
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
	 * Creates a new News model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$this->can('admin');
		$model = new News();
		$model->isPublish = 1;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			HelpModel::savePreviewPhoto($model, 'news');

			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing News model.
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

		$newBlock = new NewsBlock();
		if ($newBlock->load(Yii::$app->request->post())) {
			$newBlock->newsId = $model->id;
			if ($newBlock->save()) {
				HelpModel::saveSliderPhotos($newBlock, 'news', $model->id, HelpModel::MODEL_NEWS_SLIDER);

				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return var_dump($newBlock->errors);
			}

		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			HelpModel::savePreviewPhoto($model, 'news');

			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model'    => $model,
				'newBlock' => $newBlock
			]);
		}
	}

	/**
	 * Deletes an existing News model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->can('admin');
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the News model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return News the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		$this->can('admin');
		if (($model = News::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}

	public function actionDeleteSlider($id, $modelId, $action)
	{
		$this->can('admin');

		$picture = NewsSlider::findOne($id);
		if (!$picture) {
			throw new NotFoundHttpException('Изображение не найдено');
		}
		HelpModel::deletePhoto($picture, $picture->picture);

		return $this->redirect([$action, 'id' => $modelId]);
	}

	public function actionUpdateBlock($id)
	{
		$block = NewsBlock::findOne($id);
		if (!$block) {
			throw new NotFoundHttpException('Блок не найден');
		}
		$success = false;
		if ($block->load(Yii::$app->request->post()) && $block->save()) {
			HelpModel::saveSliderPhotos($block, 'news', $block->news->id, HelpModel::MODEL_NEWS_SLIDER);
			$success = true;
		}

		return $this->render('update-block', [
			'block'   => $block,
			'success' => $success
		]);
	}
}
