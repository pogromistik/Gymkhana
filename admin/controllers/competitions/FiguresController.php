<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\ClassHistory;
use common\models\FigureTime;
use common\models\Notice;
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
			'model'        => $model,
			'success'      => $success,
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
	
	public function actionUpdateTime($id)
	{
		$figureTime = FigureTime::findOne($id);
		if (!$figureTime) {
			throw new NotFoundHttpException('Запись не найдена');
		}
		if ($figureTime->load(\Yii::$app->request->post()) && $figureTime->save()) {
			return $this->redirect(['update', 'id' => $figureTime->figureId]);
		}
		
		return $this->render('update-time', ['figureTime' => $figureTime]);
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
	
	public function actionApproveRecord($id)
	{
		$this->can('competitions');
		
		$item = FigureTime::findOne($id);
		if (!$item) {
			return 'Запись не найдена';
		}
		
		$figure = $item->figure;
		if (!$item->recordType || $item->recordStatus != FigureTime::NEW_RECORD_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		
		switch ($item->recordType) {
			case FigureTime::RECORD_IN_RUSSIA:
				if ($figure->bestTimeInRussia && $figure->bestTimeInRussia <= $item->resultTime) {
					$transaction->rollBack();
					return 'Вы пытаетесь установить в качестве рекорда худший результат, чем текущий';
				}
				$figure->bestTimeInRussia = $item->resultTime;
				$figure->bestAthleteInRussia = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				
				$text = 'Поздравляем! Вы установили новый Российский рекорд для фигуры ' .
					$figure->title . '! Это восхитительно :)';
				$link = \Yii::$app->urlManager->createUrl(['/competitions/figure', 'id' => $figure->id]);
				Notice::add($item->athleteId, $text, $link);
				break;
			case FigureTime::RECORD_IN_WORLD:
				if ($figure->bestTime && $figure->bestTime <= $item->resultTime) {
					$transaction->rollBack();
					return 'Вы пытаетесь установить в качестве рекорда худший результат, чем текущий';
				}
				$figure->bestTime = $item->resultTime;
				$figure->bestTimeInRussia = $item->resultTime;
				$figure->bestAthlete = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				$figure->bestAthleteInRussia = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				
				$text = 'Поздравляем! Вы установили новый мировой рекорд для фигуры ' .
					$figure->title . '!! Это восхитительно! Вы - восхитительны!!';
				$link = \Yii::$app->urlManager->createUrl(['/competitions/figure', 'id' => $figure->id]);
				Notice::add($item->athleteId, $text, $link);
				break;
		}
		
		if (!$figure->save(false)) {
			$transaction->rollBack();
			
			return 'Возникла ошибка при сохранении нового рекорда для фигуры';
		}
		
		$item->recordStatus = FigureTime::NEW_RECORD_APPROVE;
		if (!$item->save(false)) {
			$transaction->rollBack();
			
			return 'Возникла ошибка при подтверждении рекорда';
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionCancelRecord($id)
	{
		$this->can('competitions');
		
		$item = FigureTime::findOne($id);
		if (!$item) {
			return 'Запись не найдена';
		}
		
		if (!$item->recordType || $item->recordStatus != FigureTime::NEW_RECORD_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		
		$item->recordStatus = FigureTime::NEW_RECORD_CANCEL;
		$item->recordType = null;
		if (!$item->save()) {
			return 'Возникла ошибка при сохранении данных';
		}
		
		return true;
	}
	
	public function actionCancelAllRecords($id)
	{
		$this->can('competitions');
		
		$figure = Figure::findOne($id);
		if (!$figure) {
			return 'Фигура не найдена';
		}
		
		$items = $figure->getResults()->andWhere(['not', ['recordType' => null]])
			->andWhere(['recordStatus' => FigureTime::NEW_RECORD_NEED_CHECK])->all();
		
		foreach ($items as $item) {
			if (!$item->recordType || $item->recordStatus != FigureTime::NEW_RECORD_NEED_CHECK) {
				return 'Запись уже была обработана';
			}
			
			$item->recordStatus = FigureTime::NEW_RECORD_CANCEL;
			$item->recordType = null;
			if (!$item->save()) {
				return 'Возникла ошибка при сохранении данных';
			}
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
		if ($item->newAthleteClassStatus != FigureTime::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
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
		
		$items = $figure->getResults()
			->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => FigureTime::NEW_CLASS_STATUS_NEED_CHECK])
			->all();
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
		
		$items = $figure->getResults()
			->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['newAthleteClassStatus' => FigureTime::NEW_CLASS_STATUS_NEED_CHECK])
			->all();
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
		if ($item->newAthleteClassStatus != FigureTime::NEW_CLASS_STATUS_NEED_CHECK) {
			return 'Запись уже была обработана';
		}
		if ($athlete->athleteClassId && $athlete->athleteClass->percent < $item->newAthleteClass->percent) {
			return 'Вы пытаетесь понизить спортсмену ' . $item->athlete->getFullName() . ' класс с ' . $athlete->athleteClass->title . ' на '
				. $item->newAthleteClass->title . '. Понижение класса невозможно';
		}
		if ($athlete->athleteClassId && $athlete->athleteClass->percent == $item->newAthleteClass->percent) {
			$item->newAthleteClassStatus = FigureTime::NEW_CLASS_STATUS_APPROVE;
			if (!$item->save()) {
				
				return 'Невозможно изменить класс спортсмену';
			}
			return true;
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
