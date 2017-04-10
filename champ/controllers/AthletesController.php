<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\ClassHistory;
use common\models\Figure;
use common\models\FigureTime;
use common\models\search\AthleteSearch;
use Yii;
use yii\web\NotFoundHttpException;

class AthletesController extends BaseController
{
	public function actionList()
	{
		$this->pageTitle = 'Спортсмены';
		$this->description = 'Спортсмены, хоть раз поучаствовавшие в соревнованиях по мотоджимхане';
		$this->keywords = 'Мотоджимхана, спортсмены, спорт, список спортсменов, мотоциклисты, рейтинг спортсменов';
		$this->layout = 'full-content';
		
		$searchModel = new AthleteSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->orderBy(['athleteClassId' => SORT_ASC, 'cityId' => SORT_ASC, 'lastName' => SORT_ASC]);
		
		return $this->render('list', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionView($id)
	{
		$athlete = Athlete::findOne($id);
		if (!$athlete) {
			throw new NotFoundHttpException('Спортсмен не найеден');
		}
		
		/** @var Figure[] $figures */
		$figures = Figure::find()->orderBy(['title' => SORT_ASC])->all();
		$figuresResult = [];
		foreach ($figures as $figure) {
			$result = FigureTime::find()->where(['figureId' => $figure->id, 'athleteId' => $athlete->id])
				->orderBy(['resultTime' => SORT_ASC])->one();
			if ($result) {
				$figuresResult[] = [
					'figure' => $figure,
					'result' => $result
				];
			}
		}
		
		$history = ClassHistory::find()->where(['athleteId' => $athlete->id])->orderBy(['date' => SORT_ASC]);
		$count = $history->count();
		if ($count > 30) {
			$offset = $count - 30;
			$history = $history->offset($offset);
		}
		$history = $history->all();
		
		$this->pageTitle = 'Спортсмены: ' . $athlete->getFullName();
		$this->description = $athlete->getFullName() . ', спортсмен мотоджимханы';
		
		return $this->render('view', [
			'athlete'       => $athlete,
			'figuresResult' => $figuresResult,
			'history'       => $history,
		]);
	}
}