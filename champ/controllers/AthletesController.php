<?php

namespace champ\controllers;

use common\models\Athlete;
use common\models\AthletesClass;
use common\models\ClassHistory;
use common\models\Country;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Participant;
use common\models\Region;
use common\models\RequestForSpecialStage;
use common\models\search\AthleteSearch;
use common\models\TranslateMessage;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class AthletesController extends BaseController
{
	public function actionList($pg = null)
	{
		$this->pageTitle = \Yii::t('app', 'Спортсмены');
		$this->description = \Yii::t('app', 'Спортсмены, хоть раз поучаствовавшие в соревнованиях по мотоджимхане');
		$this->keywords = \Yii::t('app', 'мотоджимхана') . ', ' . \Yii::t('app', 'спортсмены')
			. ', ' . \Yii::t('app', 'список спортсменов') . ', '
			. \Yii::t('app', 'мотоциклисты') . ', '
			. \Yii::t('app', 'рейтинг спортсменов');
		$this->layout = 'full-content';
		
		$searchModel = new AthleteSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $pg);
		$dataProvider->query->orderBy(['athleteClassId' => SORT_ASC, 'lastName' => SORT_ASC, 'cityId' => SORT_ASC]);
		
		return $this->render('list', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'pg'           => $pg
		]);
	}
	
	public function actionView($id)
	{
		$athlete = Athlete::findOne($id);
		if (!$athlete) {
			throw new NotFoundHttpException(\Yii::t('app', 'Спортсмен не найден'));
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
		
		$specialHistory = RequestForSpecialStage::find()->where(['status'    => RequestForSpecialStage::STATUS_APPROVE,
		                                                         'athleteId' => $athlete->id])->orderBy(['dateAdded' => SORT_DESC])->limit(30)->all();;
		
		$participants = Participant::find()->where(['athleteId' => $athlete->id])
			->andWhere(['status' => [Participant::STATUS_OUT_COMPETITION, Participant::STATUS_ACTIVE]])
			->orderBy(['dateAdded' => SORT_DESC])->limit(30)->all();
		
		$this->pageTitle = \Yii::t('app', 'Спортсмены') . ': ' . $athlete->getFullName();
		$this->description = $athlete->getFullName();
		
		return $this->render('view', [
			'athlete'        => $athlete,
			'figuresResult'  => $figuresResult,
			'history'        => $history,
			'participants'   => $participants,
			'specialHistory' => $specialHistory
		]);
	}
	
	public function actionStatsByRegions()
	{
		$this->pageTitle = \Yii::t('app', 'Статистика по регионам');
		$this->description = \Yii::t('app', 'Статистика спортсменов по регионам');
		$this->layout = 'main-with-img';
		$this->background = 'background7.png';
		
		$countryAttr = (\Yii::$app->language == TranslateMessage::LANGUAGE_RU) ? 'title' : 'title_en';
		$query = new Query();
		$query->from(['a' => Athlete::tableName(), 'b' => Region::tableName(), 'c' => AthletesClass::tableName(), 'd' => Country::tableName()]);
		$query->select(['count("a"."id")', '"b"."title" as "region"', '"c"."title" as "class"', '"d"."' . $countryAttr . '" as "country"']);
		$query->where(new Expression('"a"."athleteClassId" = "c"."id"'))
			->andWhere(new Expression('"a"."regionId" = "b"."id"'))
			->andWhere(new Expression('"b"."countryId" = "d"."id"'));
		$query->orderBy(['"b"."title"' => SORT_ASC, '"c"."title"' => SORT_ASC]);
		$query->groupBy(['"b"."title"', '"c"."title"', '"d"."' . $countryAttr . '"']);
		$items = $query->all();
		
		$stats = [];
		$totalClasses = [];
		$totalClasses['total'] = 0;
		$classes = AthletesClass::find()->select('title')->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])
			->asArray()->column();
		foreach ($items as $item) {
			if (!isset($stats[$item['region']])) {
				$stats[$item['region']] = [
					'total'   => 0,
					'groups'  => [],
					'country' => $item['country']
				];
				foreach ($classes as $class) {
					$stats[$item['region']]['groups'][$class] = 0;
				}
			}
			if (!isset($totalClasses[$item['class']])) {
				$totalClasses[$item['class']] = 0;
			}
			$totalClasses[$item['class']] += $item['count'];
			$totalClasses['total'] += $item['count'];
			$stats[$item['region']]['groups'][$item['class']] = $item['count'];
			$stats[$item['region']]['total'] += $item['count'];
		}
		
		
		return $this->render('stats-by-regions', ['stats' => $stats, 'classes' => $classes, 'totalClasses' => $totalClasses]);
	}
}