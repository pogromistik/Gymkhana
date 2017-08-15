<?php

namespace admin\controllers\competitions;

use admin\models\ReferenceTimeForm;
use admin\models\ResultTimeForm;
use common\models\Athlete;
use common\models\AthletesClass;
use common\models\CheScheme;
use common\models\City;
use admin\controllers\BaseController;
use common\models\Country;
use common\models\Figure;
use common\models\HelpModel;
use common\models\MoscowPoint;
use common\models\Region;
use common\models\search\CitySearch;
use common\models\search\YearSearch;
use common\models\Stage;
use common\models\Year;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AthleteController implements the CRUD actions for Athlete model.
 */
class HelpController extends BaseController
{
	const PHOTO_STAGE = 1;
	const PHOTO_FIGURE = 2;
	const PHOTO_ATHLETE = 3;
	
	const TYPE_CITY = 1;
	const TYPE_REGION = 2;
	
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
		$this->can('projectAdmin');
		
		$model = null;
		switch ($modelId) {
			case self::PHOTO_STAGE:
				$model = Stage::findOne($id);
				$varName = 'trackPhoto';
				break;
			case self::PHOTO_FIGURE:
				$model = Figure::findOne($id);
				$varName = 'picture';
				break;
			case self::PHOTO_ATHLETE:
				$model = Athlete::findOne($id);
				$varName = 'photo';
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
	
	public function actionCountryCategory($type)
	{
		$this->can('competitions');
		
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
		$this->can('competitions');
		
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
		$this->can('competitions');
		
		$country = Country::findOne($countryId);
		$regions = $country->regions;
		$result = [];
		foreach ($regions as $region) {
			$result[] = ['id' => $region->id, 'name' => $region->title];
		}
		
		return $result;
	}
	
	public function actionCityList($title = null, $id = null, $countryId = null)
	{
		$this->can('competitions');
		
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($title)) {
			$title = mb_strtoupper($title, 'UTF-8');
			$query = new Query();
			$query->select('"Cities"."id", ("Cities"."title" || \' (\' || "Regions"."title" || \')\') AS text')
				->from([City::tableName(), Region::tableName()])
				->where(['like', 'upper("Cities"."title")', mb_strtoupper($title, 'UTF-8')])
				->andWhere(new Expression('"Regions"."id" = "Cities"."regionId"'));
			if ($countryId) {
				$query->andWhere(['"Cities"."countryId"' => $countryId]);
			}
			$query->orderBy('CASE WHEN upper("Cities"."title") LIKE \''.$title.'\' THEN 0
			 WHEN upper("Cities"."title") LIKE \''.$title.'%\' THEN 1
			WHEN upper("Cities"."title") LIKE \'%'.$title.'%\' THEN 2 ELSE 3 END');
			$query->limit(50);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		} elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => City::findOne($id)->title];
		}
		
		return $out;
	}
	
	public function actionCities()
	{
		$this->can('competitions');
		
		$searchModel = new CitySearch();
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render('cities', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}
	
	public function actionCreateCity()
	{
		$this->can('competitions');
		
		$city = new City();
		$error = null;
		if ($city->load(\Yii::$app->request->post())) {
			$title = mb_strtoupper(trim($city->title), 'UTF-8');
			$oldCity = City::findOne(['countryId' => $city->countryId, 'regionId' => $city->regionId,
			'upper("title")' => $title]);
			if ($oldCity) {
				$error = 'В выбранном регионе уже есть город с таким названием';
			} else {
				if ($city->save()) {
					return $this->redirect('cities');
				} else {
					$error = var_dump($city->save());
				}
			}
		}
		
		return $this->render('create-city', ['city' => $city, 'error' => $error]);
	}
	
	public function actionCityUpdate($id)
	{
		$this->can('competitions');
		
		$city = City::findOne($id);
		$error = null;
		if ($city->load(\Yii::$app->request->post())) {
			$title = mb_strtoupper(trim($city->title), 'UTF-8');
			$oldCity = City::findOne(['countryId' => $city->countryId, 'regionId' => $city->regionId,
			                          'upper("title")' => $title]);
			if ($oldCity && $oldCity->id != $city->id) {
				$error = 'В выбранном регионе уже есть город с таким названием';
			} else {
				if ($city->save()) {
					return $this->redirect('cities');
				} else {
					$error = var_dump($city->save());
				}
			}
		}
		
		return $this->render('create-city', ['city' => $city, 'error' => $error]);
	}
	
	public function actionCreateRegion()
	{
		$this->can('competitions');
		
		$region = new Region();
		$error = null;
		if ($region->load(\Yii::$app->request->post())) {
			$title = mb_strtoupper(trim($region->title), 'UTF-8');
			$oldRegion = Region::findOne(['countryId'      => $region->countryId,
			                              'upper("title")' => $title]);
			if ($oldRegion) {
				$error = 'В выбранной стране уже есть регион с таким названием';
			} else {
				if ($region->save()) {
					return $this->redirect('cities');
				} else {
					$error = var_dump($region->save());
				}
			}
		}
		
		return $this->render('create-region', ['region' => $region, 'error' => $error]);
	}
	
	public function actionCreateCountry()
	{
		$this->can('competitions');
		
		$country = new Country();
		$error = null;
		if ($country->load(\Yii::$app->request->post())) {
			$title = mb_strtoupper(trim($country->title), 'UTF-8');
			$oldCountry = Country::find()->where(['upper("title")' => $title])
			->orWhere(['upper("title_en")' => $title])
				->orWhere(['upper("title_original")' => $title])->one();
			if ($oldCountry) {
				$error = 'Уже есть страна с таким названием';
			} else {
				if ($country->save()) {
					return $this->redirect('cities');
				} else {
					$error = var_dump($country->save());
				}
			}
		}
		
		return $this->render('create-country', ['country' => $country, 'error' => $error]);
	}
	
	public function actionCheScheme()
	{
		$this->can('competitions');
		$items = CheScheme::find()->orderBy('percent')->all();
		
		return $this->render('che-scheme', ['items' => $items]);
	}
	
	public function actionMoscowPointsScheme()
	{
		$this->can('competitions');
		$points = (new Query())->select('*')
			->from(['a' => MoscowPoint::tableName(), 'b' => AthletesClass::tableName()])
			->where(new Expression('"a"."class" = "b"."id"'))
			->orderBy(['b."percent"' => SORT_ASC, 'b."title"' => SORT_ASC, 'a."place"' => SORT_ASC])->all();
		$items = ArrayHelper::map($points,
			'place', 'point', 'title');
		
		return $this->render('moscow-point-scheme', ['items' => $items]);
	}
	
	public function actionTimeCalculate()
	{
		$this->can('competitions');
		$model = new ReferenceTimeForm();
		/** @var AthletesClass[] $classes */
		$classes = AthletesClass::find()->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])->all();
		$needTime = [];
		if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
			$model->calculate();
			if ($model->referenceTime) {
				/** @var AthletesClass $prev */
				$prev = null;
				$last = null;
				$prevTime = 0;
				foreach ($classes as $class) {
					if (!$prev || $prev->percent == $class->percent) {
						$startTime = '00:00.00';
					} else {
						$time = $prevTime + 10;
						$startTime = HelpModel::convertTimeToHuman($time);
					}
					$time = floor($model->referenceTime * ($class->percent) / 100);
					$time = ((int)($time / 10)) * 10;
					if (round($time / $model->referenceTime * 100, 2) >= $class->percent) {
						$time -= 10;
					}
					$prevTime = $time;
					$endTime = HelpModel::convertTimeToHuman($time);
					$needTime[$class->id] = [
						'classModel' => $class,
						'startTime'  => $startTime,
						'endTime'    => $endTime,
						'percent'    => $class->percent
					];
					$prev = $class;
					$last = $class->id;
				}
				$needTime[$last]['endTime'] = '59:59.59';
				if ($prev = AthletesClass::find()->where(['not', ['id' => $last]])->orderBy(['percent' => SORT_DESC])->one()) {
					$needTime[$last]['percent'] = '> ' . $prev->percent;
				}
			}
		}
		
		return $this->render('time-calculate', ['model' => $model, 'classes' => $classes, 'needTime' => $needTime]);
	}
	
	public function actionResultCalculate()
	{
		$this->can('competitions');
		$model = new ResultTimeForm();
		$classes = AthletesClass::find()->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])->all();
		if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
			$model->calculate();
		}
		
		return $this->render('result-calculate', ['model' => $model, 'classes' => $classes]);
	}
}
