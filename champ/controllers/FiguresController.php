<?php

namespace champ\controllers;

use common\models\Athlete;
use common\models\Figure;
use Yii;
use common\models\TmpFigureResult;
use common\models\search\TmpFigureResultSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FiguresController implements the CRUD actions for TmpFigureResult model.
 */
class FiguresController extends AccessController
{
	
	public function actionSendResult($figureId = null)
	{
		$this->pageTitle = \Yii::t('app', 'Форма для отправки своего результата по базовой фигуре');
		
		$model = new TmpFigureResult();
		$model->athleteId = \Yii::$app->user->id;
		$model->figureId = $figureId;
		
		$athlete = Athlete::findOne(\Yii::$app->user->id);
		$motorcycles = $athlete->activeMotorcycles;
		$figures = Figure::find()->orderBy(['title' => SORT_ASC])->all();
		
		return $this->render('send-result', [
			'model'       => $model,
			'motorcycles' => $motorcycles,
			'figures'     => $figures
		]);
	}
	
	public function actionSend()
	{
		$model = new TmpFigureResult();
		
		if ($model->load(\Yii::$app->request->post())) {
			if ($model->save()) {
				return true;
			}
			if (!$model->date) {
				return \Yii::t('app', 'Укажите дату заезда');
			}
			if (!$model->time) {
				return \Yii::t('app', 'Укажите время заезда');
			}
			if (!$model->videoLink) {
				return \Yii::t('app', 'Добавьте ссылку для подтверждения результата');
			}
		}
		
		return \Yii::t('app', 'Возникла ошибка при отправке данных');
	}
	
	public function actionRequests($status = null)
	{
		$searchModel = new TmpFigureResultSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['athleteId' => \Yii::$app->user->id]);
		$this->pageTitle = \Yii::t('app', 'Заявки на добавление результатов базовых фигур');
		if ($status) {
			switch ($status) {
				case TmpFigureResult::STATUS_NEW:
					$this->pageTitle = \Yii::t('app', 'Новые заявки на добавление результатов базовых фигур');
					$dataProvider->query->andWhere(['isNew' => 1]);
					break;
				case TmpFigureResult::STATUS_CANCEL:
					$this->pageTitle = \Yii::t('app', 'Отменённые заявки на добавление результатов базовых фигур');
					$dataProvider->query->andWhere(['isNew' => 0])->andWhere(['not', ['cancelReason' => null]]);
					break;
				case TmpFigureResult::STATUS_APPROVE:
					$this->pageTitle = \Yii::t('app', 'Подтверждённые заявки на добавление результатов базовых фигур');
					$dataProvider->query->andWhere(['isNew' => 0])->andWhere(['not', ['figureResultId' => null]]);
					break;
			}
		}
		$dataProvider->query->orderBy(['dateUpdated' => SORT_DESC]);
		
		$this->layout = 'full-content';
		
		return $this->render('requests', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}
