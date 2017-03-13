<?php

namespace admin\controllers\competitions;

use common\models\Athlete;
use common\models\City;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\Notice;
use common\models\Participant;
use common\models\search\TmpFigureResultSearch;
use common\models\TmpFigureResult;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\TmpParticipant;
use common\models\search\TmpParticipantSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TmpParticipantController implements the CRUD actions for TmpParticipant model.
 */
class TmpFiguresController extends Controller
{
	public function actions()
	{
		return [
			'update' => [
				'class'       => EditableAction::className(),
				'modelClass'  => TmpFigureResult::className(),
				'forceCreate' => false
			]
		];
	}
	
	/**
	 * Lists all TmpParticipant models.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TmpFigureResultSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['isNew' => 1]);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single TmpParticipant model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Finds the TmpParticipant model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return TmpParticipant the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = TmpParticipant::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionFindAthletes($lastName)
	{
		$lastName = mb_strtoupper($lastName, 'UTF-8');
		$athletes = Athlete::find()->where(['upper("lastName")' => $lastName])->orWhere(['upper("lastName")' => $lastName])->all();
		
		return $this->renderAjax('_athletes', ['athletes' => $athletes, 'lastName' => $lastName]);
	}
	
	public function actionAddAndRegistration($id)
	{
		$tmpParticipant = TmpParticipant::findOne($id);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		if ($tmpParticipant->cityId) {
			$city = City::findOne($tmpParticipant->cityId);
			if (!$city) {
				$transaction->rollBack();
				
				return 'Город не найден';
			}
		} else {
			$city = City::findOne(['upper("title")' => mb_strtoupper($tmpParticipant->city, 'UTF-8')]);
			if (!$city) {
				$transaction->rollBack();
				
				return 'Город отсутствует в системе';
			}
		}
		
		$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$athlete = new Athlete();
		$athlete->lastName = $tmpParticipant->lastName;
		$athlete->firstName = $tmpParticipant->firstName;
		$athlete->cityId = $city->id;
		$athlete->phone = $tmpParticipant->phone;
		if (!$athlete->save()) {
			$transaction->rollBack();
			
			return var_dump($athlete->errors);
		}
		
		$motorcycle = new Motorcycle();
		$motorcycle->athleteId = $athlete->id;
		$motorcycle->mark = $tmpParticipant->motorcycleMark;
		$motorcycle->model = $tmpParticipant->motorcycleModel;
		if (!$motorcycle->save()) {
			$transaction->rollBack();
			
			return var_dump($motorcycle->errors);
		}
		
		$participant = new Participant();
		$participant->athleteId = $athlete->id;
		$participant->motorcycleId = $motorcycle->id;
		if ($tmpParticipant->number) {
			$participant->number = $tmpParticipant->number;
		}
		$participant->stageId = $tmpParticipant->stageId;
		$participant->championshipId = $tmpParticipant->championshipId;
		if (!$participant->save()) {
			$transaction->rollBack();
			
			return var_dump($participant->errors);
		}
		
		$tmpParticipant->athleteId = $athlete->id;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionCancel($id)
	{
		$tmpParticipant = TmpParticipant::findOne($id);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
		if (!$tmpParticipant->save()) {
			return var_dump($tmpParticipant->errors);
		}
		
		return true;
	}
	
	public function actionRegistration($tmpParticipantId, $athleteId, $motorcycleId)
	{
		$tmpParticipant = TmpParticipant::findOne($tmpParticipantId);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		$motorcycle = Motorcycle::findOne(['id' => $motorcycleId, 'athleteId' => $athleteId]);
		if (!$motorcycle) {
			return 'Мотоцикл не найден';
		}
		
		$old = Participant::find()->where(['stageId' => $tmpParticipant->stageId])
			->andWhere(['athleteId' => $athleteId])->andWhere(['motorcycleId' => $motorcycleId])->all();
		if ($old) {
			return 'Спортсмен уже зарегистрирован на этап на этом мотоцикле';
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		
		$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
		$tmpParticipant->athleteId = $athlete->id;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$participant = new Participant();
		$participant->athleteId = $athlete->id;
		$participant->motorcycleId = $motorcycle->id;
		if ($tmpParticipant->number) {
			$participant->number = $tmpParticipant->number;
		}
		$participant->stageId = $tmpParticipant->stageId;
		$participant->championshipId = $tmpParticipant->championshipId;
		if (!$participant->save()) {
			$transaction->rollBack();
			
			return var_dump($participant->errors);
		}
		
		$tmpParticipant->athleteId = $athlete->id;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionAddMotorcycleAndRegistration($tmpParticipantId, $athleteId)
	{
		$tmpParticipant = TmpParticipant::findOne($tmpParticipantId);
		if (!$tmpParticipant) {
			return 'Запись не найдена';
		}
		
		$athlete = Athlete::findOne($athleteId);
		if (!$athlete) {
			return 'Спортсмен не найден';
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		
		$tmpParticipant->status = TmpParticipant::STATUS_PROCESSED;
		$tmpParticipant->athleteId = $athlete->id;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$motorcycle = new Motorcycle();
		$motorcycle->athleteId = $athlete->id;
		$motorcycle->mark = $tmpParticipant->motorcycleMark;
		$motorcycle->model = $tmpParticipant->motorcycleModel;
		if (!$motorcycle->save()) {
			$transaction->rollBack();
			
			return var_dump($motorcycle->errors);
		}
		
		$participant = new Participant();
		$participant->athleteId = $athlete->id;
		$participant->motorcycleId = $motorcycle->id;
		if ($tmpParticipant->number) {
			$participant->number = $tmpParticipant->number;
		}
		$participant->stageId = $tmpParticipant->stageId;
		$participant->championshipId = $tmpParticipant->championshipId;
		if (!$participant->save()) {
			$transaction->rollBack();
			
			return var_dump($participant->errors);
		}
		
		$tmpParticipant->athleteId = $athlete->id;
		if (!$tmpParticipant->save()) {
			$transaction->rollBack();
			
			return var_dump($tmpParticipant->errors);
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionApprove($id)
	{
		$tmp = TmpFigureResult::findOne($id);
		if (!$tmp) {
			return 'Результат не найден';
		}
		if (!$tmp->isNew) {
			return 'Результат уже был обработан';
		}
		$figureResult = new FigureTime();
		$figureResult->athleteId = $tmp->athleteId;
		$figureResult->motorcycleId = $tmp->motorcycleId;
		$figureResult->time = $tmp->time;
		$figureResult->fine = $tmp->fine;
		$figureResult->figureId = $tmp->figureId;
		$figureResult->date = $tmp->date;
		$figureResult->timeForHuman = $tmp->timeForHuman;
		$figureResult->dateForHuman = $tmp->dateForHuman;
		
		$transaction = \Yii::$app->db->beginTransaction();
		if (!$figureResult->save()) {
			$transaction->rollBack();
			
			return 'Возникла ошибка при сохранении данных';
		}
		
		$tmp->isNew = 0;
		$tmp->figureResultId = $figureResult->id;
		if (!$tmp->save()) {
			$transaction->rollBack();
			
			return 'Возникла ошибка при сохранении данных';
		}
		
		$figure = $figureResult->figure;
		$link = Url::to(['/competitions/figure', 'id' => $figure->id]);
		$min = str_pad(floor($figureResult->time / 60000), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor(($figureResult->time - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
		$mls = str_pad(($figureResult->time - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
		$timeForHuman = $min . ':' . $sec . '.' . $mls;
		$text = 'Ваш результат ' . $timeForHuman . ' для фигуры ' . $figure->title . ' подтверждён.';
		
		Notice::add($tmp->athleteId, $text, $link);
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionCancelResult()
	{
		$id = \Yii::$app->request->post('id');
		if (!$id) {
			return 'Результат не найден';
		}
		$text = \Yii::$app->request->post('reason');
		if (!$text) {
			return 'Необходимо указать причину';
		}
		if (mb_strlen($text) > 255) {
			return 'Причина должна содержать не более 255 символов';
		}
		$tmp = TmpFigureResult::findOne($id);
		if (!$tmp) {
			return 'Результат не найден';
		}
		if (!$tmp->isNew) {
			return 'Результат уже был обработан';
		}
		
		$tmp->isNew = 0;
		$tmp->cancelReason = $text;
		if (!$tmp->save()) {
			return 'Возникла ошибка при сохранении данных';
		}
		
		$figure = $tmp->figure;
		$link = Url::to(['/figures/requests', 'status' => TmpFigureResult::STATUS_CANCEL]);
		$text = 'Ваш результат для фигуры ' . $figure->title . ' отклонён. Чтобы узнать подробности, перейдите по ссылке.';
		
		Notice::add($tmp->athleteId, $text, $link);
		
		return true;
	}
}
