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
}
