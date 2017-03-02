<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\ClassHistory;
use common\models\FigureTime;
use common\models\search\FigureTimeSearch;
use Yii;
use common\models\Figure;
use common\models\search\FigureSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FiguresController implements the CRUD actions for Figure model.
 */
class FiguresController extends BaseController
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
		
		$searchModel = new FigureTimeSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['figureId' => $model->id]);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id, 'success' => true]);
		}
		
		return $this->render('update', [
			'model'   => $model,
			'success' => $success,
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionAddResults($figureId, $date, $success = false, $error = false)
	{
		$date = (new \DateTime($date, new \DateTimeZone('Asia/Yekaterinburg')))->setTime(0, 0,
			0)->getTimestamp();
		$figure = $this->findModel($figureId);
		$figureTime = new FigureTime();
		$figureTime->figureId = $figure->id;
		$figureTime->date = $date;
		
		return $this->render('add-results', [
			'date'       => $date,
			'figureTime' => $figureTime,
			'success'    => $success,
			'error'      => $error,
			'figure'     => $figure
		]);
	}
	
	public function actionAddTime()
	{
		$figureTime = new FigureTime();
		$figureTime->load(\Yii::$app->request->post());
		if ($figureTime->save()) {
			return true;
		} else {
			return var_dump($figureTime->errors);
		}
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
	
	public function actionApproveClass($id)
	{
		$this->can('competitions');
		
		$item = FigureTime::findOne($id);
		if (!$item) {
			return 'Запись не найдена';
		}
		
		$result = $this->approveClass($item);
		if ($result !== true) {
			return $result;
		}
		
		return true;
	}
	
	public function actionCancelClass($id)
	{
		$this->can('competitions');
		
		$item = FigureTime::findOne($id);
		if (!$item) {
			return 'Запись не найдена';
		}
		
		$item->newAthleteClassId = null;
		$item->newAthleteClassStatus = FigureTime::NEW_CLASS_STATUS_CANCEL;
		if (!$item->save()) {
			return var_dump($item->errors);
		}
		
		return true;
	}
	
	public function actionApproveAllClasses($id)
	{
		$this->can('competitions');
		
		$figure = Figure::findOne($id);
		if (!$figure) {
			return 'Фигура не найдена';
		}
		
		$items = $figure->results;
		foreach ($items as $item) {
			$result = $this->approveClass($item);
			if ($result !== true) {
				return $result;
			}
		}
		
		return true;
	}
	
	public function actionCancelAllClasses($id)
	{
		$this->can('competitions');
		
		$figure = Figure::findOne($id);
		if (!$figure) {
			return 'Фигура не найдена';
		}
		
		$items = $figure->results;
		foreach ($items as $item) {
			$item->newAthleteClassId = null;
			$item->newAthleteClassStatus = FigureTime::NEW_CLASS_STATUS_CANCEL;
			if (!$item->save()) {
				return var_dump($item->errors);
			}
		}
		
		return true;
	}
	
	public function approveClass(FigureTime $item)
	{
		$athlete = $item->athlete;
		if ($athlete->athleteClass->percent < $item->newAthleteClass->percent) {
			return 'Вы пытаетесь понизить спортсмену класс с ' . $athlete->athleteClass->title . ' на '
				. $item->newAthleteClass->title . '. Понижение класса невозможно';
		}
		
		if ($athlete->athleteClassId != $item->newAthleteClassId) {
			$transaction = \Yii::$app->db->beginTransaction();
			
			$event = $item->figure->title;
			$history = ClassHistory::create($athlete->id, $item->motorcycleId,
				$athlete->athleteClassId, $item->newAthleteClassId, $event,
				$item->resultTime, $item->figure->bestTime, $item->percent);
			if (!$history) {
				$transaction->rollBack();
				
				return 'Возникла ошибка при изменении данных';
			}
			
			$athlete->athleteClassId = $item->newAthleteClassId;
			if (!$athlete->save()) {
				$transaction->rollBack();
				
				return 'Невозможно изменить класс спортсмену';
			}
			
			$item->newAthleteClassStatus = FigureTime::NEW_CLASS_STATUS_APPROVE;
			if (!$item->save()) {
				$transaction->rollBack();
				
				return 'Невозможно сохранить изменения для участника';
			}
			$transaction->commit();
		}
		
		return true;
	}
}
