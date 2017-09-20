<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Athlete;
use common\models\FigureTime;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class DeveloperController extends BaseController
{
	public function init()
	{
		parent::init();
		$this->can('developer');
	}
	
	public function actionRepeatAthletes()
	{
		$lastNames = Athlete::find()->select('lastName')->groupBy('lastName')->having('count(*) > 1')->asArray()->column();
		$athletes = Athlete::find()->where(['lastName' => $lastNames])->orderBy(['lastName' => SORT_ASC])->all();
		
		return $this->render('repeat-athletes', [
			'athletes' => $athletes
		]);
	}
	
	public function actionRepeatFiguresTime()
	{
		$repeats = FigureTime::find()->select(['athleteId', 'resultTime', 'figureId'])->groupBy(['athleteId', 'resultTime', 'figureId'])
			->having('count(*) > 1')->asArray()->all();
		$items = [];
		foreach ($repeats as $repeat) {
			$items = array_merge($items, FigureTime::findAll(['figureId' => $repeat['figureId'], 'athleteId' => $repeat['athleteId'], 'resultTime' => $repeat['resultTime']]));
		}
		return $this->render('repeat-figures-time', [
			'items' => $items
		]);
	}
}
