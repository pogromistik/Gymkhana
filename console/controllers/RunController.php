<?php

namespace console\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\City;
use common\models\Country;
use common\models\Error;
use common\models\Motorcycle;
use common\models\Region;
use common\models\Stage;
use yii\console\Controller;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class RunController extends Controller
{
	public static $timezones = [
		'Калининградская область'                     => ['Europe/Kaliningrad', '+2'],
		'Псковская область'                           => ['Europe/Moscow', '+3'],
		'Ивановская область'                          => ['Europe/Moscow', '+3'],
		'Ставропольский край'                         => ['Europe/Moscow', '+3'],
		'Московская область'                          => ['Europe/Moscow', '+3'],
		'Мордовия'                                    => ['Europe/Moscow', '+3'],
		'Новгородская область'                        => ['Europe/Moscow', '+3'],
		'Пензенская область'                          => ['Europe/Moscow', '+3'],
		'Калужская область'                           => ['Europe/Moscow', '+3'],
		'Татарстан'                                   => ['Europe/Moscow', '+3'],
		'Воронежская область'                         => ['Europe/Moscow', '+3'],
		'Ростовская область'                          => ['Europe/Moscow', '+3'],
		'Волгоградская область'                       => ['Europe/Moscow', '+3'],
		'Ленинградская область'                       => ['Europe/Moscow', '+3'],
		'Чувашская'                                   => ['Europe/Moscow', '+3'],
		'Краснодарский край'                          => ['Europe/Moscow', '+3'],
		'Кировская область'                           => ['Europe/Moscow', '+3'],
		'Рязанская область'                           => ['Europe/Moscow', '+3'],
		'Нижегородская область'                       => ['Europe/Moscow', '+3'],
		'Липецкая область'                            => ['Europe/Moscow', '+3'],
		'Астраханская область'                        => ['Europe/Samara', '+4'],
		'Ульяновская область'                         => ['Europe/Samara', '+4'],
		'Саратовская область'                         => ['Europe/Samara', '+4'],
		'Самарская область'                           => ['Europe/Samara', '+4'],
		'Удмуртская'                                  => ['Europe/Samara', '+4'],
		'Ханты-Мансийский Автономный округ - Югра АО' => ['Asia/Yekaterinburg', '+5'],
		'Челябинская область'                         => ['Asia/Yekaterinburg', '+5'],
		'Уфимский район'                              => ['Asia/Yekaterinburg', '+5'],
		'Свердловская область'                        => ['Asia/Yekaterinburg', '+5'],
		'Оренбургская область'                        => ['Asia/Yekaterinburg', '+5'],
		'Пермский край'                               => ['Asia/Yekaterinburg', '+5'],
		'Башкортостан'                                => ['Asia/Yekaterinburg', '+5'],
		'Курганская область'                          => ['Asia/Yekaterinburg', '+5'],
		'Тюменская область'                           => ['Asia/Yekaterinburg', '+5'],
		'Ямало-Ненецкий  АО'                          => ['Asia/Yekaterinburg', '+5'],
		'Омская область'                              => ['Asia/Omsk', '+6'],
		'Новосибирская область'                       => ['Asia/Novosibirsk', '+7'],
		'Томская область'                             => ['Asia/Krasnoyarsk', '+7'],
		'Ярославская область'                         => ['Asia/Krasnoyarsk', '+7'],
		'Красноярский край'                           => ['Asia/Krasnoyarsk', '+7'],
		'Алтайский край'                              => ['Asia/Barnaul', '+7'],
		'Кемеровская область'                         => ['Asia/Krasnoyarsk', '+7'],
		'Иркутская область'                           => ['Asia/Irkutsk', '+8'],
		'Бурятия'                                     => ['Asia/Irkutsk', '+8'],
		'Саха /Якутия/'                               => ['Asia/Yakutsk', '+9'],
		'Приморский край'                             => ['Asia/Vladivostok', '+10'],
		'Магаданская область'                         => ['Asia/Magadan', '+11'],
		'Сахалинская область'                         => ['Asia/Magadan', '+11'],
		'Камчатский край'                             => ['Asia/Kamchatka', '+12']
	];
	
	public function actionUpdateRegions()
	{
		$count = 0;
		foreach (self::$timezones as $timezone => $info) {
			$region = Region::findOne(['title' => $timezone]);
			if (!$region) {
				echo 'Region not found ' . $info[0] . PHP_EOL;
				
				return false;
			}
			$count += City::updateAll(['timezone' => $info[0], 'utc' => $info[1]], ['regionId' => $region->id]);
		}
		echo 'Update ' . $count . ' items';
		
		return true;
	}
	
	public function actionCleanIncrement($tableName, $pk)
	{
		\Yii::$app->db->createCommand('SELECT setval(\'"' . $tableName . '_' . $pk . '_seq"\'::regclass, MAX("' . $pk . '")) FROM "' . $tableName . '"')->execute();
	}
	
	public function actionCleanCitiesIncrement()
	{
		\Yii::$app->db->createCommand('SELECT setval(\'"Russia_id_seq"\'::regclass, MAX("id")) FROM "Cities"')->execute();
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
		$subQuery->from('cities_all');
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
					file_put_contents('text.txt', $citiesAndRegion['id'] . '-' . $citiesAndRegion['city'] . ': ' . 'region not found', FILE_APPEND);
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
				if (in_array($citiesAndRegion['city'], $oldCities) && $citiesAndRegion['biggest_city'] == 't') {
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
	
	public function actionNotUniqueCities()
	{
		$titles = City::find()->select("title")->where(['countryId' => 1])->groupBy("title")->having('count(*)>1')->asArray()->column();
		$regionIds = City::find()->select("regionId")->where(['title' => $titles])->distinct()->asArray()->column();
		$regions = ArrayHelper::map(Region::findAll($regionIds), 'id', 'title');
		$items = City::find()->where(['title' => $titles]);
		$count = 0;
		foreach ($items->batch(1000) as $data) {
			echo $count . PHP_EOL;
			/** @var City $city */
			foreach ($data as $city) {
				$city->title = $city->title . ' (' . $regions[$city->regionId] . ')';
				$city->save(false);
				$count++;
			}
		}
		echo 'success' . PHP_EOL;
		
		return false;
	}
	
	public function actionDeleteCities()
	{
		$citiesQuery = City::find();
		$delete = 0;
		$countItems = 0;
		foreach ($citiesQuery->batch() as $cities) {
			echo $countItems . PHP_EOL;
			/** @var City $city */
			foreach ($cities as $city) {
				if (!$city->link) {
					$count = City::find()->where(['title' => $city->title, 'regionId' => $city->regionId])->count();
					if ($count > 1) {
						$city->delete();
						$delete++;
					}
				}
				$countItems++;
			}
		}
		
		echo 'Delete ' . $delete . ' items' . PHP_EOL;
		
		return true;
	}
	
	public function actionInsertAthletes()
	{
		$filePath = 'admin/web/files/athletes.xlsx';
		
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
						case 'A':
							$array[$i]['lastName'] = trim($cell->getFormattedValue());
							break;
						case 'B':
							$array[$i]['firstName'] = trim($cell->getFormattedValue());
							break;
						case 'C':
							$array[$i]['city'] = trim($cell->getFormattedValue());
							break;
						case 'D':
							$array[$i]['mark'] = trim($cell->getFormattedValue());
							break;
						case 'E':
							$array[$i]['model'] = trim($cell->getFormattedValue());
							break;
						case 'F':
							$array[$i]['number'] = trim($cell->getFormattedValue());
							break;
					}
				}
			}
		}
		
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$athlete = Athlete::find()->where(['and', ['upper("firstName")' => mb_strtoupper($data['firstName'])], ['upper("lastName")' => mb_strtoupper($data['lastName'])]])
				->orWhere(['and', ['upper("lastName")' => mb_strtoupper($data['firstName'])], ['upper("firstName")' => mb_strtoupper($data['lastName'])]])->one();
			if (!$athlete) {
				$city = City::findOne(['upper("title")' => mb_strtoupper($data['city'])]);
				if (!$city) {
					echo $i . ' city not found' . PHP_EOL;
					
					return false;
				}
				$athlete = new Athlete();
				$athlete->lastName = $data['lastName'];
				$athlete->firstName = $data['firstName'];
				$athlete->countryId = 1;
				$athlete->regionId = $city->regionId;
				$athlete->cityId = $city->id;
				if ($data['number'] && $data['number'] != '') {
					$athlete->number = $data['number'];
				}
				if (!$athlete->save()) {
					return var_dump($athlete->errors);
				}
			}
			$motorcycle = Motorcycle::find()->where(['upper("model")' => mb_strtoupper($data['model'])])
				->andWhere(['upper("mark")' => mb_strtoupper($data['mark'])])->andWhere(['athleteId' => $athlete->id])->one();
			if (!$motorcycle) {
				$motorcycle = new Motorcycle();
				$motorcycle->mark = $data['mark'];
				$motorcycle->model = $data['model'];
				$motorcycle->athleteId = $athlete->id;
				if (!$motorcycle->save()) {
					return var_dump($motorcycle->errors);
				}
			}
		}
		
		return true;
	}
	
	public function actionChangeChampionshipsStatus()
	{
		$time = time();
		$count = 0;
		
		//чемпионат прошел
		$championships = Championship::findAll(['status' => Championship::STATUS_PRESENT]);
		foreach ($championships as $championship) {
			$stages = $championship->stages;
			/** @var Stage $stage */
			foreach ($stages as $stage) {
				if (!$stage->dateOfThe || $stage->dateOfThe > $time) {
					continue 2;
				}
			}
			$championship->status = Championship::STATUS_PAST;
			$championship->save();
			$count++;
		}
		
		//чемпионат начался
		$championships = Championship::findAll(['status' => Championship::STATUS_UPCOMING]);
		foreach ($championships as $championship) {
			if (Stage::find()->where(['championshipId' => $championship->id])
				->andWhere(['<=', 'dateOfThe', $time])->one()
			) {
				$championship->status = Championship::STATUS_PRESENT;
				$championship->save();
				$count++;
			}
		}
		
		echo 'Change ' . $count . ' items';
		
		return true;
	}
	
	public function actionChangeStagesStatus()
	{
		$time = time();
		//открыта регистрация на этап
		Stage::updateAll(['status' => Stage::STATUS_START_REGISTRATION], [
			'and',
			['status' => Stage::STATUS_UPCOMING],
			['not', ['startRegistration' => null]],
			['<=', 'startRegistration', $time]
		]);
		
		//завершена регистрация на этап
		Stage::updateAll(['status' => Stage::STATUS_END_REGISTRATION], [
			'and',
			['status' => Stage::STATUS_START_REGISTRATION],
			['not', ['endRegistration' => null]],
			['<=', 'endRegistration', $time]
		]);
		
		//текущий этап
		Stage::updateAll(['status' => Stage::STATUS_PRESENT], [
			'and',
			['<=', 'dateOfThe', $time],
			['not', ['dateOfThe' => null]],
			['status' => [Stage::STATUS_UPCOMING, Stage::STATUS_END_REGISTRATION, Stage::STATUS_END_REGISTRATION]]
		]);
		
		//прошедший этап
		/** @var Stage[] $stages */
		$stages = Stage::find()->where(['not', ['status' => Stage::STATUS_PAST]])->all();
		foreach ($stages as $stage) {
			if ($stage->dateOfThe && ($stage->dateOfThe + 86400) <= $time) {
				$stage->status = Stage::STATUS_PAST;
				$stage->save();
			}
		}
		
		return true;
	}
	
	public function actionChangePhotoStatus()
	{
		Stage::updateAll(['trackPhotoStatus' => Stage::PHOTO_PUBLISH], [
			'and',
			['not', ['trackPhoto' => null]],
			['trackPhotoStatus' => Stage::PHOTO_NOT_PUBLISH],
			['status' => Stage::STATUS_PAST]
		]);
		
		return true;
	}
	
	public function actionCheckSize()
	{
		exec('df -h', $output, $return_var);
		if ($output) {
			if (!isset($output[1])) {
				$errors = new Error();
				$errors->text = 'Невозможно проверить остаток дискового пространства на хостинге';
				$errors->save();
				
				mail('nadia__@bk.ru', 'Ошибка на соревновательном сайте', 'Невозможно проверить остаток дискового пространства на хостинге');
				return false;
			}
			$string = $output[1];
			$array = explode('G', $string);
			if (!isset($array[2])) {
				$errors = new Error();
				$errors->text = 'Невозможно проверить остаток дискового пространства на хостинге';
				$errors->save();
				
				mail('nadia__@bk.ru', 'Ошибка на соревновательном сайте', 'Невозможно проверить остаток дискового пространства на хостинге');
				return false;
			}
			$size = trim($array[2]);
			echo $size . PHP_EOL;
			if ($size < 1) {
				$errors = new Error();
				$errors->type = Error::TYPE_CRITICAL_ERROR;
				$errors->text = 'На хостинге осталось менее 1GB свободного места';
				$errors->save();
			} elseif ($size <= 2) {
				$errors = new Error();
				$errors->text = 'На хостинге осталось ' . $size . 'GB свободного места';
				$errors->type = Error::TYPE_DB;
				$errors->save();
			}
		}
		
		return true;
	}
}