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
	
	/**
	 * Lists all TmpFigureResult models.
	 *
	 * @return mixed
	 */
	public function actionSendResult()
	{
		$this->pageTitle = 'Отправить результат базовой фигуры';
		
		$model = new TmpFigureResult();
		$model->athleteId = \Yii::$app->user->id;
		
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
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return true;
		}
		
		return 'Возникла ошибка при отправке данных';
	}
	
	public function actionRequests($status = null)
	{
		$searchModel = new TmpFigureResultSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['athleteId' => \Yii::$app->user->id]);
		$this->pageTitle = 'Заявки на добавление результатов базовых фигур';
		if ($status) {
			switch ($status) {
				case TmpFigureResult::STATUS_NEW:
					$this->pageTitle = 'Новые заявки на добавление результатов базовых фигур';
					$dataProvider->query->andWhere(['isNew' => 1]);
					break;
				case TmpFigureResult::STATUS_CANCEL:
					$this->pageTitle = 'Отменённые заявки на добавление результатов базовых фигур';
					$dataProvider->query->andWhere(['isNew' => 0])->andWhere(['not', ['cancelReason' => null]]);
					break;
				case TmpFigureResult::STATUS_APPROVE:
					$this->pageTitle = 'Подтверждённые заявки на добавление результатов базовых фигур';
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