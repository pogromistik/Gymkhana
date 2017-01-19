<?php

namespace admin\controllers;

use common\models\AboutSlider;
use common\models\Contacts;
use common\models\HelpModel;
use common\models\Page;
use common\models\Regular;
use common\models\search\RegularSearch;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\AboutBlock;
use common\models\search\AboutBlockSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AboutController implements the CRUD actions for AboutBlock model.
 */
class AboutController extends BaseController
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
				'modelClass'  => AboutSlider::className(),
				'forceCreate' => false
			]
		];
	}
	
	public function actionIndex()
	{
		$searchModel = new AboutBlockSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$page = Page::findOne(['layoutId' => 'about']);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'page'         => $page
		]);
	}
	
	/**
	 * Displays a single AboutBlock model.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new AboutBlock model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new AboutBlock();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			HelpModel::saveSliderPhotos($model, 'about', $model->id, HelpModel::MODEL_ABOUT_SLIDER);
			
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing AboutBlock model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			HelpModel::saveSliderPhotos($model, 'about', $model->id, HelpModel::MODEL_ABOUT_SLIDER);
			
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing AboutBlock model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$sliderPictures = AboutSlider::findAll(['blockId' => $id]);
		foreach ($sliderPictures as $picture) {
			HelpModel::deletePhoto($picture, $picture->picture);
		}
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the AboutBlock model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return AboutBlock the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = AboutBlock::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionDeleteSlider($id, $modelId)
	{
		$this->can('admin');
		
		$picture = AboutSlider::findOne($id);
		if (!$picture) {
			throw new NotFoundHttpException('Изображение не найдено');
		}
		HelpModel::deletePhoto($picture, $picture->picture);
		
		return $this->redirect(['update', 'id' => $modelId]);
	}
	
	public function actionRegular()
	{
		$this->can('admin');
		
		$page = Page::findOne(['layoutId' => 'regulars']);
		if (!$page) {
			throw new NotFoundHttpException('Страница не найдена');
		}
		
		$searchModel = new RegularSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('regular', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'page'         => $page
		]);
	}
	
	public function actionCreateRegular()
	{
		$this->can('admin');
		
		$model = new Regular();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update-regular', 'id' => $model->id]);
		} else {
			return $this->render('create-regular', [
				'model' => $model,
			]);
		}
	}
	
	public function actionUpdateRegular($id)
	{
		$this->can('admin');
		
		if (($model = Regular::findOne($id)) !== null) {
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update-regular', [
				'model' => $model,
			]);
		}
	}
	
	public function actionDeleteRegular($id)
	{
		$this->can('admin');
		
		if (($model = Regular::findOne($id)) !== null) {
			$model->delete();
			
			return $this->redirect(['regular']);
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionContacts($success = null)
	{
		$this->can('admin');
		
		if (!$model = Contacts::find()->one()) {
			$model = new Contacts();
		}
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['contacts', 'success' => true]);
		}
		
		return $this->render('contacts', ['model' => $model, 'success' => $success]);
	}
}
