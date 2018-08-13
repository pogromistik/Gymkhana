<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Country;
use common\models\RequestForSpecialStage;

class StatsController extends BaseController
{
	public function actionSpecialChamp()
	{
		$this->can("changeSpecialChamps");
		
		$countAll = RequestForSpecialStage::find()->count();
		$byStatus = RequestForSpecialStage::find()->select(["COUNT(id)", "status"])->groupBy('status')->orderBy('status')->indexBy('status')->asArray()->all();
		$unique = RequestForSpecialStage::find()->select("athleteId")->distinct()->count();
		
		/** @var RequestForSpecialStage[] $requests */
		$requests = RequestForSpecialStage::find()->where(['not', ['athleteId' => null]])->orderBy(['dateAdded' => SORT_ASC])->all();
		$athleteIds = [];
		$byCountries = [];
		$byClasses = [];
		$byCities = [];
		foreach ($requests as $request) {
			if (in_array($request->athleteId, $athleteIds) !== false) {
				continue;
			}
			$athleteIds[] = $request->athleteId;
			$athlete = $request->athlete;
			
			//По странам
			if (!isset($byCountries[$athlete->countryId])) {
				$byCountries[$athlete->countryId] = [
					'title' => $athlete->country->title,
					'count' => 1
				];
			} else {
				$byCountries[$athlete->countryId]["count"]++;
			}
			
			//По городам
			if ($athlete->countryId === Country::RUSSIA_ID) {
				if (!isset($byCities[$athlete->cityId])) {
					$byCities[$athlete->cityId] = [
						'title' => $athlete->city->title . ' (' . $athlete->region->title . ')',
						'count' => 1
					];
				} else {
					$byCities[$athlete->cityId]["count"]++;
				}
			}
			
			//По изменению классов
			if ($request->athleteClassId !== $athlete->athleteClassId) {
				if (!isset($byClasses[$request->athleteClassId])) {
					$byClasses[$request->athleteClassId] = [
						'classTitle' => $request->athleteClass->title,
						'newClass'   => []
					];
				}
				if (!isset($byClasses[$request->athleteClassId]['newClass'][$athlete->athleteClassId])) {
					$byClasses[$request->athleteClassId]['newClass'][$athlete->athleteClassId] = [
						'title' => $athlete->athleteClass->title,
						'count' => 1
					];
				} else {
					$byClasses[$request->athleteClassId]['newClass'][$athlete->athleteClassId]['count']++;
				}
				ksort($byClasses[$request->athleteClassId]['newClass']);
			}
		}
		ksort($byClasses);
		uasort($byCountries, "self::cmp");
		uasort($byCities, "self::cmp");
		
		return $this->render('special-champ', [
			'byClasses'   => $byClasses,
			'byCities'    => $byCities,
			'byCountries' => $byCountries,
			'byStatus'    => $byStatus,
			'unique'      => $unique,
			'countAll'    => $countAll
		]);
	}
	
	private function cmp($a, $b)
	{
		return ($a['count'] > $b['count']) ? -1 : 1;
	}
}