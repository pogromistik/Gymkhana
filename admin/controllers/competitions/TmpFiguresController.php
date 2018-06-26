<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\City;
use common\models\ClassHistory;
use common\models\Country;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Motorcycle;
use common\models\Notice;
use common\models\Participant;
use common\models\search\TmpFigureResultSearch;
use common\models\TmpFigureResult;
use dosamigos\editable\EditableAction;
use Yii;
use common\models\TmpParticipant;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * TmpParticipantController implements the CRUD actions for TmpParticipant model.
 */
class TmpFiguresController extends BaseController
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
	
	public function init()
	{
		parent::init();
		$this->can('canApproveFigureResults');
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
	
	/**
	 * @param $id
	 *
	 * @return bool|string
	 * @throws \yii\db\Exception
	 */
	public function actionApprove($id)
	{
		$tmp = TmpFigureResult::findOne($id);
		if (!$tmp) {
			return 'Результат не найден';
		}
		if (!$tmp->isNew) {
			return 'Результат уже был обработан';
		}
		
		if (\Yii::$app->mutex->acquire('TmpFigures-' . $tmp->id, 10)) {
			$figureResult = new FigureTime();
			$figureResult->athleteId = $tmp->athleteId;
			$figureResult->motorcycleId = $tmp->motorcycleId;
			$figureResult->time = $tmp->time;
			$figureResult->fine = $tmp->fine;
			$figureResult->figureId = $tmp->figureId;
			$figureResult->date = $tmp->date;
			$figureResult->timeForHuman = $tmp->timeForHuman;
			$figureResult->dateForHuman = $tmp->dateForHuman;
			if ($tmp->videoLink) {
				if (mb_strstr($tmp->videoLink, 'http://', 'UTF-8') !== false
					|| mb_strstr($tmp->videoLink, 'https://', 'UTF-8') !== false) {
					if (mb_strstr($tmp->videoLink, 'http://vk.', 'UTF-8') !== false
						|| mb_strstr($tmp->videoLink, 'https://vk.', 'UTF-8') !== false) {
						if (mb_strstr($tmp->videoLink, 'video', 'UTF-8') !== false) {
							$figureResult->videoLink = $tmp->videoLink;
						}
					} else {
						$figureResult->videoLink = $tmp->videoLink;
					}
				}
			}
			
			$transaction = \Yii::$app->db->beginTransaction();
			if (!$figureResult->save()) {
				\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
				$transaction->rollBack();
				
				return 'Возникла ошибка при сохранении данных';
			}
			
			$tmp->isNew = 0;
			$tmp->figureResultId = $figureResult->id;
			if (!$tmp->save()) {
				\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
				$transaction->rollBack();
				
				return 'Возникла ошибка при сохранении данных';
			}
			
			$figure = $figureResult->figure;
			$link = Url::to(['/competitions/figure', 'id' => $figure->id]);
			$min = str_pad(floor($figureResult->time / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($figureResult->time - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($figureResult->time - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$timeForHuman = $min . ':' . $sec . '.' . $mls;
			$text = \Yii::t('app', 'Ваш результат {time} для фигуры {title} подтверждён.',
				['time' => $timeForHuman, 'title' => $figure->title], $tmp->athlete->language);
			
			Notice::add($tmp->athleteId, $text, $link);
			
			if (!$this->changeClass($tmp->athlete, $figureResult)) {
				\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
				$transaction->rollBack();
				
				return 'В момент изменения класса спортсмену возникла ошибка. Обратитесь к администратору.';
			}
			
			if (!$this->setRecord($figureResult)) {
				\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
				$transaction->rollBack();
				
				return 'В момент установления рекорда для фигуры возникла ошибка. Обратитесь к администратору.';
			}
			
			\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
			$transaction->commit();
		} else {
			\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
	
	private function changeClass(Athlete $athlete, FigureTime $item)
	{
		if (!$item->newAthleteClass) {
			return true;
		}
		if ($athlete->athleteClassId && $athlete->athleteClass->percent < $item->newAthleteClass->percent) {
			return true;
		}
		if ($athlete->athleteClassId && $athlete->athleteClass->percent == $item->newAthleteClass->percent) {
			return true;
		}
		
		if ($athlete->athleteClassId != $item->newAthleteClassId) {
			$event = $item->figure->title;
			$history = ClassHistory::create($athlete->id, $item->motorcycleId,
				$athlete->athleteClassId, $item->newAthleteClassId, $event,
				$item->resultTime, $item->figure->bestTime, $item->percent);
			if (!$history) {
				return false;
			}
			
			$athlete->athleteClassId = $item->newAthleteClassId;
			if (!$athlete->save()) {
				return false;
			}
		}
		
		return true;
	}
	
	private function setRecord(FigureTime $item)
	{
		if (!$item->recordType) {
			return true;
		}
		$figure = Figure::findOne($item->figureId);
		switch ($item->recordType) {
			case FigureTime::RECORD_IN_RUSSIA:
				if ($figure->bestTimeInRussia && $figure->bestTimeInRussia <= $item->resultTime) {
					return true;
				}
				$figure->bestTimeInRussia = $item->resultTime;
				$figure->bestAthleteInRussia = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				
				$text = \Yii::t('app', 'Поздравляем! Вы установили новый Российский рекорд для фигуры {title}! Это восхитительно :)',
					['title' => $figure->title], $item->athlete->language);
				$link = \Yii::$app->urlManager->createUrl(['/competitions/figure', 'id' => $figure->id]);
				Notice::add($item->athleteId, $text, $link);
				break;
			case FigureTime::RECORD_IN_WORLD:
				if ($figure->bestTime && $figure->bestTime <= $item->resultTime) {
					return true;
				}
				$figure->bestTime = $item->resultTime;
				$figure->bestAthlete = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				
				if ($item->athlete->countryId == Country::RUSSIA_ID) {
					$figure->bestTimeInRussia = $item->resultTime;
					$figure->bestAthleteInRussia = $item->athlete->getFullName() . ', ' . $item->motorcycle->getFullTitle();
				}
				
				$text = \Yii::t('app',
					'Поздравляем! Вы установили новый Российский рекорд для фигуры {title}!! Это восхитительно! Вы - восхитительны!!',
					['title' => $figure->title], $item->athlete->language);
				$link = \Yii::$app->urlManager->createUrl(['/competitions/figure', 'id' => $figure->id]);
				Notice::add($item->athleteId, $text, $link);
				break;
		}
		
		if (!$figure->save(false)) {
			return false;
		}
		
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
		if (mb_strlen($text, 'UTF-8') > 255) {
			return 'Причина должна содержать не более 255 символов';
		}
		$tmp = TmpFigureResult::findOne($id);
		if (!$tmp) {
			return 'Результат не найден';
		}
		if (!$tmp->isNew) {
			return 'Результат уже был обработан';
		}
		
		if (\Yii::$app->mutex->acquire('TmpFigures-' . $tmp->id, 10)) {
			$tmp->isNew = 0;
			$tmp->cancelReason = $text;
			if (!$tmp->save()) {
				\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
				
				return 'Возникла ошибка при сохранении данных';
			}
			
			$figure = $tmp->figure;
			$link = Url::to(['/figures/requests', 'status' => TmpFigureResult::STATUS_CANCEL]);
			$text = \Yii::t('app', 'Ваш результат для фигуры {title} отклонён. Чтобы узнать подробности, перейдите по ссылке.',
				['title' => $figure->title], $tmp->athlete->language);
			
			Notice::add($tmp->athleteId, $text, $link);
		} else {
			\Yii::$app->mutex->release('TmpFigures-' . $tmp->id);
			
			return 'Информация устарела. Пожалуйста, перезагрузите страницу';
		}
		
		return true;
	}
}
