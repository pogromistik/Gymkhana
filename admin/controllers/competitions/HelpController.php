<?php

namespace admin\controllers\competitions;

use common\models\City;
use admin\controllers\BaseController;
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
		if (!$cityTitle) {
			$result['error'] = true;
			return $result;
		}
		$cityTitle = trim($cityTitle);
		$city = City::findOne(['upper(title)' => mb_strtoupper($cityTitle)]);
		if ($city) {
			$result['hasCity'] = true;
			return $result;
		}
		$city = new City();
		$city->title = $cityTitle;
		$city->showInRussiaPage = 0;
		if (!$city->save()) {
			$result['error'] = true;
			return $result;
		}
		$result['success'] = true;
		return $result;
	}
}
