<?php

namespace admin\controllers\competitions;

use common\models\City;
use admin\controllers\BaseController;
use common\models\HelpModel;
use common\models\Region;
use common\models\search\YearSearch;
use common\models\Stage;
use common\models\Year;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class HelpController extends BaseController
{
	const PHOTO_STAGE = 1;
	const PHOTO_FIGURE = 2;
	
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
	
	public function actionYears()
	{
		$this->can('competitions');
		
		$searchModel = new YearSearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('years', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionYearView($yearId = null, $success = false)
	{
		$this->can('competitions');
		
		if ($yearId) {
			$year = Year::findOne($yearId);
			if (!$year) {
				throw new NotFoundHttpException();
			}
		} else {
			$year = new Year();
		}
		
		if ($year->load(\Yii::$app->request->post()) && $year->save()) {
			return $this->redirect(['year-view', 'yearId' => $year->id, 'success' => true]);
		}
		
		return $this->render('year-view', [
			'year'    => $year,
			'success' => $success
		]);
	}
	
	public function actionDeletePhoto($id, $modelId)
	{
		$model = null;
		switch ($modelId) {
			case self::PHOTO_STAGE:
				$model = Stage::findOne($id);
				$varName = 'trackPhoto';
				break;
		}
		if (!$model) {
			return 'Возникла ошибка при удалении фотографии';
		}
		if ($model->$varName) {
			HelpModel::deleteFile($model->$varName);
			$model->$varName = null;
			if (!$model->save()) {
				return 'Возникла ошибка при сохранении изменений';
			}
		}
		
		return true;
	}
}
