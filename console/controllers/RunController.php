<?php

namespace console\controllers;

use common\models\Athlete;
use common\models\City;
use common\models\AboutBlock;
use common\models\AboutSlider;
use common\models\Album;
use common\models\Contacts;
use common\models\Country;
use common\models\DopPage;
use common\models\FigureTime;
use common\models\Files;
use common\models\GroupMenu;
use common\models\HelpProject;
use common\models\Layout;
use common\models\Link;
use common\models\MainMenu;
use common\models\MainPhoto;
use common\models\Marshal;
use common\models\MenuItem;
use common\models\News;
use common\models\NewsBlock;
use common\models\NewsSlider;
use common\models\Page;
use common\models\Region;
use common\models\Regular;
use common\models\Stage;
use common\models\Track;
use common\models\Year;
use yii\console\Controller;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class RunController extends Controller
{
	public function actionCleanIncrement($tableName, $pk)
	{
		\Yii::$app->db->createCommand('SELECT setval(\'"' . $tableName . '_' . $pk . '_seq"\'::regclass, MAX("' . $pk . '")) FROM "' . $tableName . '"')->execute();
	}
	
	public function actionAddRegions()
	{
		$athletes = Athlete::find()->all();
		foreach ($athletes as $item) {
			$item->regionId = $item->city->regionId;
			$item->save();
		}
		$stages = Stage::find()->all();
		foreach ($stages as $item) {
			$item->regionId = $item->city->regionId;
			$item->save();
		}
		
		return true;
	}
	
	public function actionCleanCities()
	{
		City::updateAll(['showInRussiaPage' => 0], ['top' => null]);
		
		return true;
	}
	
	public static function actionInsertCountry()
	{
		Country::deleteAll();
		$filePath = 'admin/web/files/country.xlsx';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
		$worksheet = $objPHPExcel->getWorksheetIterator()->current();
		
		$array = [];
		
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($worksheet->getRowIterator() as $i => $row) {
			$cellIterator = $row->getCellIterator();
			/**
			 * @var \PHPExcel_Cell $cell
			 */
			foreach ($cellIterator as $j => $cell) {
				if ($cell->getFormattedValue() !== null) {
					switch ($j) {
						case 'A':
							$array[$i]['id'] = $cell->getFormattedValue();
							break;
						case 'B':
							$array[$i]['title'] = $cell->getFormattedValue();
							break;
					}
				}
			}
		}
		
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$country = Country::findOne(['title' => (string)$data['title']]);
			if (!$country) {
				$country = new Country();
				$country->id = $data['id'];
				$country->title = (string)$data['title'];
				if (!$country->save()) {
					$transaction->rollBack();
					var_dump($country->errors);
					
					return false;
				}
			}
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionInsertRegions()
	{
		$filePath = 'admin/web/files/regions.xlsx';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
		$worksheet = $objPHPExcel->getWorksheetIterator()->current();
		
		$array = [];
		
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($worksheet->getRowIterator() as $i => $row) {
			$cellIterator = $row->getCellIterator();
			/**
			 * @var \PHPExcel_Cell $cell
			 */
			foreach ($cellIterator as $j => $cell) {
				if ($cell->getFormattedValue() !== null) {
					switch ($j) {
						case 'B':
							$array[$i]['country'] = $cell->getFormattedValue();
							break;
						case 'C':
							$array[$i]['title'] = $cell->getFormattedValue();
							break;
					}
				}
			}
		}
		
		$countries = ArrayHelper::map(Country::find()->all(), 'id', 'id');
		
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			if ($data['title'] == 'Москва и Московская область') {
				$data['title'] = 'Московская область';
			} elseif ($data['title'] == 'Санкт-Петербург и область') {
				$data['region'] = 'Ленинградская область';
			}
			$region = Region::findOne(['upper("title")' => mb_strtoupper($data['title'])]);
			if (!$region) {
				$region = new Region();
				$region->title = (string)$data['title'];
			}
			$region->countryId = $countries[$data['country']];
			if (!$region->save()) {
				$transaction->rollBack();
				var_dump($region->errors);
				
				return false;
			}
		}
		
		Region::deleteAll(['countryId' => null]);
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionInsertCities()
	{
		City::deleteAll(['link' => null]);
		City::deleteAll(['link' => '']);
		$filePath = 'admin/web/files/regions.xlsx';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
		$worksheet = $objPHPExcel->getWorksheetIterator()->current();
		$regions = [];
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($worksheet->getRowIterator() as $i => $row) {
			$cellIterator = $row->getCellIterator();
			/**
			 * @var \PHPExcel_Cell $cell
			 */
			foreach ($cellIterator as $j => $cell) {
				if ($cell->getFormattedValue() !== null) {
					switch ($j) {
						case 'A':
							$i = $cell->getFormattedValue();
							$regions[$i] = '';
							break;
						case 'C':
							$title = $cell->getFormattedValue();
							if ($title == 'Москва и Московская область') {
								$title = 'Московская область';
							} elseif ($title == 'Санкт-Петербург и область') {
								$title = 'Ленинградская область';
							}
							if (!trim($title)) {
								unset($regions[$i]);
							}
							$regions[$i] = $title;
							break;
					}
				}
			}
		}
		
		$regionsIds = ArrayHelper::map(Region::find()->all(), 'id', 'title');
		$countryIds = ArrayHelper::map(Region::find()->all(), 'id', 'countryId');
		
		/* =====================================================================
		=====================================================================
		=====================================================================
		=====================================================================
		=====================================================================*/
		
		$filePath = 'admin/web/files/cities.xlsx';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
		$worksheet = $objPHPExcel->getWorksheetIterator()->current();
		
		$array = [];
		
		foreach ($worksheet->getRowIterator() as $i => $row) {
			$cellIterator = $row->getCellIterator();
			/**
			 * @var \PHPExcel_Cell $cell
			 */
			foreach ($cellIterator as $j => $cell) {
				if ($cell->getFormattedValue() !== null) {
					switch ($j) {
						case 'B':
							$array[$i]['region'] = $cell->getFormattedValue();
							break;
						case 'C':
							if ($cell->getFormattedValue() == 'Великий Новгород (Новгород)') {
								$array[$i]['title'] = 'Великий Новгород';
							} elseif ($cell->getFormattedValue() == 'Нарофоминск') {
								$array[$i]['title'] = 'Наро-фоминск';
							} else {
								$array[$i]['title'] = $cell->getFormattedValue();
							}
							if (!$array[$i]['title']) {
								unset($array[$i]);
							}
							break;
					}
				}
			}
		}
		
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$city = City::findOne(['upper("title")' => mb_strtoupper($data['title'])]);
			if (!$city) {
				$city = new City();
				$city->title = $data['title'];
			}
			$regionTitle = $regions[$data['region']];
			$regionId = array_search($regionTitle, $regionsIds);
			if (!$regionId) {
				return var_dump($data['region']);
			}
			$city->regionId = $regionId;
			$city->countryId = $countryIds[$regionId];
			if (!$city->save()) {
				$transaction->rollBack();
				var_dump($city->errors);
				
				foreach ($city->errors as $attr => $error) {
					foreach ($error as $info) {
						file_put_contents('text.txt', $data['title'] . ' - ' . $attr . ': ' . $info, FILE_APPEND);
					}
				}
				
				return false;
			}
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function actionTranslateCountries()
	{
		$filePath = 'admin/web/files/translate.xlsx';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filePath);
		$worksheet = $objPHPExcel->getWorksheetIterator()->current();
		$array = [];
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($worksheet->getRowIterator() as $i => $row) {
			$cellIterator = $row->getCellIterator();
			/**
			 * @var \PHPExcel_Cell $cell
			 */
			foreach ($cellIterator as $j => $cell) {
				if ($cell->getFormattedValue() !== null) {
					switch ($j) {
						case 'A':
							$array[$i]['ru'] = $cell->getFormattedValue();
							break;
						case 'B':
							$array[$i]['en'] = $cell->getFormattedValue();
							break;
					}
				}
			}
		}
		
		$count = 0;
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$country = Country::findOne(['upper("title")' => mb_strtoupper($data['ru'])]);
			if ($country) {
				$country->title_en = $data['en'];
				$count++;
				if (!$country->save()) {
					$transaction->rollBack();
					
					return var_dump($country->errors);
				}
			}
		}
		$transaction->commit();
		
		echo $count . ' from ' . Country::find()->count() . PHP_EOL;
		
		return true;
	}
	
	public function actionInsertCountries()
	{
		include('admin/web/files/countries.php');
		Country::deleteAll();
		$i = 0;
		foreach (array_chunk($countries, 100) as $chunk) {
			echo $i . PHP_EOL;
			foreach ($chunk as $countryData) {
				$country = new Country();
				$country->id = $countryData['country_id'];
				$country->title = $countryData['name'];
				if (!$country->save()) {
					foreach ($country->errors as $attr => $error) {
						foreach ($error as $info) {
							file_put_contents('text.txt', $attr . ': ' . $info, FILE_APPEND);
						}
					}
				}
			}
			$i += 100;
		}
		echo 'success!' . PHP_EOL;
		
		return true;
	}
	
	public function actionInsertRegion()
	{
		Region::deleteAll();
		$subQuery = new Query();
		$subQuery->select('*, rank() over (partition by "region" order by "id" asc) n');
		$subQuery->from('cities');
		$results = new Query();
		$results->from('(' . $subQuery->createCommand()->rawSql . ') A');
		$results->where(new Expression('n=1'));
		$results->orderBy(['a."region"' => SORT_ASC]);
		$count = 0;
		foreach ($results->all() as $result) {
			if ($result['region'] && $result['region'] != '') {
				$region = new Region();
				$region->title = $result['region'];
				$region->countryId = $result['country_id'];
				if (!$region->save()) {
					foreach ($region->errors as $attr => $error) {
						foreach ($error as $info) {
							file_put_contents('text.txt', $result['region'] . '-' . $attr . ': ' . $info, FILE_APPEND);
							echo 'error' . PHP_EOL;
							
							return false;
						}
					}
				}
				echo $count++ . PHP_EOL;
			}
		}
		
		return 'success';
	}
	
	public function actionInsertCity()
	{
		City::deleteAll(['or', ['link' => null], ['link' => '']]);
		$citiesAndRegionsQuery = (new Query())->from('cities_all')->orderBy(['id' => SORT_ASC]);
		$count = 0;
		$regions = ArrayHelper::map(Region::find()->all(), 'id', 'title');
		$oldCities = City::find()->select('title')->asArray()->column();
		foreach ($citiesAndRegionsQuery->batch() as $citiesAndRegions) {
			echo $count . PHP_EOL;
			$transaction = \Yii::$app->db->beginTransaction();
			foreach ($citiesAndRegions as $citiesAndRegion) {
				if (!$citiesAndRegion['region'] || $citiesAndRegion['region'] == '') {
					if ($citiesAndRegion['city'] == 'Москва') {
						$citiesAndRegion['region'] = 'Московская область';
					} elseif ($citiesAndRegion['city'] == 'Санкт-Петербург') {
						$citiesAndRegion['region'] = 'Санкт-Петербург город';
					} elseif ($citiesAndRegion['city'] == 'Таянды') {
						$citiesAndRegion['region'] = 'Челябинская область';
					} elseif ($citiesAndRegion['city'] == 'Малое Василево') {
						$citiesAndRegion['region'] = 'Тверская область';
					} else {
						$citiesAndRegion['region'] = 'Other';
					}
				}
				if ($citiesAndRegion['region'] == 'Other') {
					$region = Region::findOne(['title' => 'Other', 'countryId' => $citiesAndRegion['country_id']]);
					if (!$region) {
						$region = new Region();
						$region->title = 'Other';
						$region->countryId = $citiesAndRegion['country_id'];
						$region->save();
					}
					$regionId = $region->id;
				} else {
					$regionId = array_search($citiesAndRegion['region'], $regions);
				}
				
				if (!$regionId) {
					$transaction->rollBack();
					file_put_contents('text.txt', $citiesAndRegion['id'] . '-' . $citiesAndRegion['title'] . ': ' . 'region not found', FILE_APPEND);
					echo 'error' . PHP_EOL;
					
					return false;
				}
				
				/** @var City $city */
				if (in_array($citiesAndRegion['city'], $oldCities)) {
					$city = City::find()->where(['upper("title")' => mb_strtoupper($citiesAndRegion['city'])])->one();
				} else {
					$city = new City();
					$city->title = $citiesAndRegion['city'];
				}
				$city->countryId = $citiesAndRegion['country_id'];
				$city->regionId = $regionId;
				$city->state = $citiesAndRegion['state'];
				if (!$city->save()) {
					foreach ($city->errors as $attr => $error) {
						foreach ($error as $info) {
							$transaction->rollBack();
							file_put_contents('text.txt', $citiesAndRegion['id'] . '-' . $attr . ': ' . $info, FILE_APPEND);
							
							echo 'error' . PHP_EOL;
							
							return false;
						}
					}
				}
				$count++;
			}
			$transaction->commit();
		}
		
		echo 'success';
		
		return true;
	}
	
	public function actionInsertCitiesAndRegions()
	{
		//Region::deleteAll();
		//City::deleteAll(['or', ['link' => null], ['link' => '']]);
		$citiesAndRegionsQuery = (new Query())->from('cities')->offset(365000)->orderBy(['id' => SORT_ASC]);
		$count = 0;
		$oldCities = City::find()->select('title')->asArray()->column();
		foreach ($citiesAndRegionsQuery->batch(1000) as $citiesAndRegions) {
			echo $count . PHP_EOL;
			$transaction = \Yii::$app->db->beginTransaction();
			foreach ($citiesAndRegions as $citiesAndRegion) {
				if (!$citiesAndRegion['region'] || $citiesAndRegion['region'] == '') {
					if ($citiesAndRegion['city'] == 'Москва') {
						$citiesAndRegion['region'] = 'Московская область';
					} elseif ($citiesAndRegion['city'] == 'Санкт-Петербург') {
						$citiesAndRegion['region'] = 'Санкт-Петербург город';
					} elseif ($citiesAndRegion['city'] == 'Таянды') {
						$citiesAndRegion['region'] = 'Челябинская область';
					} elseif ($citiesAndRegion['city'] == 'Малое Василево') {
						$citiesAndRegion['region'] = 'Тверская область';
					} else {
						$citiesAndRegion['region'] = 'Other';
					}
				}
				$region = Region::findOne(['upper("title")' => mb_strtoupper($citiesAndRegion['region'])]);
				if (!$region) {
					$region = new Region();
					$region->title = $citiesAndRegion['region'];
					$region->countryId = $citiesAndRegion['country_id'];
					if (!$region->save()) {
						foreach ($region->errors as $attr => $error) {
							foreach ($error as $info) {
								$transaction->rollBack();
								file_put_contents('text.txt', $citiesAndRegion['id'] . '-' . $attr . ': ' . $info, FILE_APPEND);
								
								return false;
							}
						}
					}
				}
				
				/** @var City $city */
				if (in_array($citiesAndRegion['city'], $oldCities)) {
					$city = City::find()->where(['upper("title")' => mb_strtoupper($citiesAndRegion['city'])])->one();
				} else {
					$city = City::find()->where(['upper("title")' => mb_strtoupper($citiesAndRegion['city'])])
						->andWhere(['regionId' => $region->id])->one();
				}
				if (!$city) {
					$city = new City();
					$city->title = $citiesAndRegion['city'];
				}
				$city->countryId = $citiesAndRegion['country_id'];
				$city->regionId = $region->id;
				$city->state = $citiesAndRegion['state'];
				if (!$city->save()) {
					foreach ($city->errors as $attr => $error) {
						foreach ($error as $info) {
							$transaction->rollBack();
							file_put_contents('text.txt', $citiesAndRegion['id'] . '-' . $attr . ': ' . $info, FILE_APPEND);
							
							return false;
						}
					}
				}
				$count++;
			}
			$transaction->commit();
		}
		
		echo 'success ' . $count . PHP_EOL;
		
		return true;
	}
	
	public function actionFixes()
	{
		$regionsQuery = City::find();
		$count = 0;
		foreach ($regionsQuery->batch(1000) as $regions) {
			echo $count . PHP_EOL;
			foreach ($regions as $region) {
				$count++;
				$region->title = html_entity_decode($region->title);
				$region->save();
			}
		}
		
		return true;
	}
}