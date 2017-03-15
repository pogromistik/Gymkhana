<?php

namespace champ\controllers;


use common\models\City;
use common\models\Country;
use yii\db\Query;
use yii\web\Controller;
use yii\helpers\Json;
use yii\web\Response;

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
		$cities = $country->getCities()->limit(50)->all();
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
	
	public function actionCityList($title = null, $id = null, $countryId = null) {
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($title)) {
			$query = new Query();
			$query->select('id, title AS text')
				->from(City::tableName())
				->where(['like', 'upper("title")', mb_strtoupper($title)])
				->andWhere(['countryId' => $countryId])
				->limit(20);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => City::findOne($id)->title];
		}
		return $out;
	}
	
	public function actionCountryList($title = null, $id = null) {
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($title)) {
			$query = new Query();
			$query->select('id, title AS text')
				->from(Country::tableName())
				->where(['like', 'title', $title])
				->limit(20);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Country::findOne($id)->title];
		}
		return $out;
	}
}
