<?php

namespace champ\controllers;


use common\models\Country;
use yii\web\Controller;
use yii\helpers\Json;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class HelpController extends Controller
{
	const TYPE_CITY = 1;
	const TYPE_REGION = 2;
	
	public function actionCountryCategory($type)
	{
		if (isset($_POST['depdrop_parents'])) {
			$parent = $_POST['depdrop_parents'];
			if ($parent != null) {
				$countryId = $parent[0];
				$out = null;
				switch ($type) {
					case self::TYPE_CITY:
						$out = self::getCitiesSubCatList($countryId);
						break;
					case self::TYPE_REGION:
						$out = self::getRegionsSubCatList($countryId);
						break;
				}
				echo Json::encode(['output' => $out, 'selected' => '']);
				
				return;
			}
		}
		echo Json::encode(['output' => '', 'selected' => '']);
	}
	
	public function getCitiesSubCatList($countryId)
	{
		$country = Country::findOne($countryId);
		$cities = $country->cities;
		$result = [];
		foreach ($cities as $city) {
			$result[] = ['id' => $city->id, 'name' => $city->title];
		}
		
		return $result;
	}
	
	public function getRegionsSubCatList($countryId)
	{
		$country = Country::findOne($countryId);
		$regions = $country->regions;
		$result = [];
		foreach ($regions as $region) {
			$result[] = ['id' => $region->id, 'name' => $region->title];
		}
		
		return $result;
	}
}
