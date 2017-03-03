<?php

namespace admin\controllers\competitions;

use common\models\City;
use admin\controllers\BaseController;
use common\models\Region;
use yii\web\Response;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class HelpController extends BaseController
{
	public function actionAddCity()
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error'   => false,
			'hasCity' => false,
			'success' => false
		];
		$cityTitle = \Yii::$app->request->post('city');
		$regionId = \Yii::$app->request->post('regionId');
		
		if (!$cityTitle) {
			$result['error'] = 'Необходимо указать город';
			
			return $result;
		}
		
		if (!$regionId) {
			$result['error'] = 'Необходимо указать регион';
			
			return $result;
		}
		
		$region = City::findOne($regionId);
		if (!$region) {
			$result['error'] = 'Регион не найден';
			
			return $result;
		}
		
		$cityTitle = trim($cityTitle);
		$city = City::findOne(['upper(title)' => mb_strtoupper($cityTitle, 'UTF-8')]);
		if ($city) {
			$result['hasCity'] = true;
			
			return $result;
		}
		$city = new City();
		$city->title = $cityTitle;
		$city->showInRussiaPage = 0;
		$city->region = $region->id;
		if (!$city->save()) {
			$result['error'] = true;
			
			return $result;
		}
		$result['success'] = true;
		
		return $result;
	}
	
	public function actionAddRegion()
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$result = [
			'error'   => false,
			'hasCity' => false,
			'success' => false
		];
		
		$region = \Yii::$app->request->post('region');
		
		if (!$region) {
			$result['error'] = 'Необходимо указать регион';
			
			return $result;
		}
		
		$region = Region::findOne(['upper(title)' => mb_strtoupper($region, 'UTF-8')]);
		if ($region) {
			$result['hasRegion'] = true;
			
			return $result;
		}
		
		$region = new Region();
		$region->title = $region;
		if (!$region->save()) {
			$result['error'] = var_dump($region);
			return $result;
		}
		
		$result['error'] = true;
		return $result;
	}
}
