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
	public function actionCountryCategory()
	{
		if (isset($_POST['depdrop_parents'])) {
			$parent = $_POST['depdrop_parents'];
			if ($parent != null) {
				$countryId = $parent[0];
				$out = self::getSubCatList($countryId);
				echo Json::encode(['output' => $out, 'selected' => '']);
				
				return;
			}
		}
		echo Json::encode(['output' => '', 'selected' => '']);
	}
	
	public function getSubCatList($countryId)
	{
		$country = Country::findOne($countryId);
		$cities = $country->cities;
		$result = [];
		foreach ($cities as $city) {
			$result[] = ['id' => $city->id, 'name' => $city->title];
		}
		
		return $result;
	}
}
