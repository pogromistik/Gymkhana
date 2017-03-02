<?php

namespace admin\controllers\competitions;

use common\models\FigureTime;
use Yii;
use common\models\Figure;
use common\models\search\FigureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FiguresController implements the CRUD actions for Figure model.
 */
class FiguresController extends Controller
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
	 * Lists all Figure models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new FigureSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Creates a new Figure model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Figure();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	public function actionUpdate($id, $success = false)
	{
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
	
	public function actionAddResults($date, $success = true, $error = false)
	{
		$date = (new \DateTime($date, new \DateTimeZone('Asia/Yekaterinburg')))->setTime(0, 0,
			0)->getTimestamp();
		$figureTime = new FigureTime();
		
		return $this->render('add-results', [
			'date'       => $date,
			'figureTime' => $figureTime,
			'success'    => $success,
			'error'      => $error
		]);
	}
	
	/**
	 * Finds the Figure model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Figure the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Figure::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
