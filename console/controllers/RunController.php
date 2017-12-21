<?php

namespace console\controllers;

use common\models\Athlete;
use common\models\AthletesClass;
use common\models\Championship;
use common\models\CheScheme;
use common\models\City;
use common\models\ClassHistory;
use common\models\Country;
use common\models\Error;
use common\models\Figure;
use common\models\FigureTime;
use common\models\HelpModel;
use common\models\InternalClass;
use common\models\MoscowPoint;
use common\models\Motorcycle;
use common\models\NewsSubscription;
use common\models\Notice;
use common\models\Participant;
use common\models\Region;
use common\models\RegionalGroup;
use common\models\RequestForSpecialStage;
use common\models\SpecialChamp;
use common\models\SpecialStage;
use common\models\Stage;
use common\models\SubscriptionQueue;
use common\models\Time;
use common\models\TmpAthlete;
use common\models\TmpFigureResult;
use common\models\TmpParticipant;
use common\models\TranslateMessage;
use common\models\TranslateMessageSource;
use common\models\Year;
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
	
	public function actionUpdateParticipantsArrived()
	{
		Participant::updateAll(['isArrived' => 1],
			['status' => [Participant::STATUS_OUT_COMPETITION, Participant::STATUS_ACTIVE]]);
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
	
	public function actionMailTest()
	{
		\Yii::$app->mailer->compose('text', ['text' => 'проверка почты'])
			->setTo('nadia__@bk.ru')
			->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
			->setSubject('gymkhana-cup.ru: проверка почты')
			->send();
	}
	
	public function actionIndices()
	{
		$transaction = \Yii::$app->db->beginTransaction();
		
		$this->createIndex('Athlete_firstName', Athlete::tableName(), 'firstName');
		$this->createIndex('Athlete_lastName', Athlete::tableName(), 'lastName');
		$this->createIndex('Athlete_email', Athlete::tableName(), 'email');
		$this->createIndex('Athlete_number', Athlete::tableName(), 'number');
		$this->createForeign('Athlete_cityId', Athlete::tableName(), 'cityId', City::tableName(), 'id');
		$this->createForeign('Athlete_athleteClassId', Athlete::tableName(), 'athleteClassId', AthletesClass::tableName(), 'id');
		$this->createForeign('Athlete_regionId', Athlete::tableName(), 'regionId', Region::tableName(), 'id');
		$this->createForeign('Athlete_countryId', Athlete::tableName(), 'countryId', Country::tableName(), 'id');
		
		$this->createIndex('Championship_isClosed', Championship::tableName(), 'isClosed');
		$this->createIndex('Championship_useCheScheme', Championship::tableName(), 'useCheScheme');
		$this->createForeign('Championship_yearId', Championship::tableName(), 'yearId', Year::tableName(), 'id');
		$this->createForeign('Championship_regionId', Championship::tableName(), 'regionId', Region::tableName(), 'id');
		$this->createForeign('Championship_regionGroupId', Championship::tableName(), 'regionGroupId', RegionalGroup::tableName(), 'id');
		
		$this->createIndex('CheScheme_percent', CheScheme::tableName(), 'percent');
		
		$this->createForeign('City_countryId', City::tableName(), 'countryId', Country::tableName(), 'id');
		
		$this->createForeign('ClassHistory_athleteId', ClassHistory::tableName(), 'athleteId', Athlete::tableName(), 'id');
		$this->createForeign('ClassHistory_motorcycleId', ClassHistory::tableName(), 'motorcycleId', Motorcycle::tableName(), 'id');
		$this->createForeign('ClassHistory_oldClassId', ClassHistory::tableName(), 'oldClassId', AthletesClass::tableName(), 'id');
		$this->createForeign('ClassHistory_newClassId', ClassHistory::tableName(), 'newClassId', AthletesClass::tableName(), 'id');
		
		$this->createIndex('Country_title', Country::tableName(), 'title');
		
		$this->createForeign('FigureTime_figureId', FigureTime::tableName(), 'figureId', Figure::tableName(), 'id');
		$this->createForeign('FigureTime_athleteId', FigureTime::tableName(), 'athleteId', Athlete::tableName(), 'id');
		$this->createForeign('FigureTime_motorcycleId', FigureTime::tableName(), 'motorcycleId', Motorcycle::tableName(), 'id');
		$this->createForeign('FigureTime_athleteClassId', FigureTime::tableName(), 'athleteClassId', AthletesClass::tableName(), 'id');
		$this->createForeign('FigureTime_newAthleteClassId', FigureTime::tableName(), 'newAthleteClassId', AthletesClass::tableName(), 'id');
		
		$this->createIndex('Figure_useForClassesCalculate', Figure::tableName(), 'useForClassesCalculate');
		
		$this->createForeign('InternalClass_championshipId', InternalClass::tableName(), 'championshipId', Championship::tableName(), 'id');
		
		$this->createIndex('Motorcycle_mark', Motorcycle::tableName(), 'mark');
		$this->createIndex('Motorcycle_model', Motorcycle::tableName(), 'model');
		$this->createForeign('Motorcycle_athleteId', Motorcycle::tableName(), 'athleteId', Athlete::tableName(), 'id');
		$this->createForeign('Motorcycle_internalClassId', Motorcycle::tableName(), 'internalClassId', InternalClass::tableName(), 'id');
		
		$this->createIndex('Notice_status', Notice::tableName(), 'status');
		$this->createForeign('Notice_athleteId', Notice::tableName(), 'athleteId', Athlete::tableName(), 'id');
		
		$this->createIndex('Participant_percent', Participant::tableName(), 'percent');
		$this->createIndex('Participant_points', Participant::tableName(), 'points');
		$this->createForeign('Participant_championshipId', Participant::tableName(), 'championshipId', Championship::tableName(), 'id');
		$this->createForeign('Participant_stageId', Participant::tableName(), 'stageId', Stage::tableName(), 'id');
		$this->createForeign('Participant_athleteId', Participant::tableName(), 'athleteId', Athlete::tableName(), 'id');
		$this->createForeign('Participant_motorcycleId', Participant::tableName(), 'motorcycleId', Motorcycle::tableName(), 'id');
		$this->createForeign('Participant_internalClassId', Participant::tableName(), 'internalClassId', InternalClass::tableName(), 'id');
		$this->createForeign('Participant_athleteClassId', Participant::tableName(), 'athleteClassId', AthletesClass::tableName(), 'id');
		$this->createForeign('Participant_newAthleteClassId', Participant::tableName(), 'newAthleteClassId', AthletesClass::tableName(), 'id');
		
		$this->createForeign('Region_countryId', Region::tableName(), 'countryId', Country::tableName(), 'id');
		
		$this->createIndex('Stage_startRegistration', Stage::tableName(), 'startRegistration');
		$this->createIndex('Stage_endRegistration', Stage::tableName(), 'endRegistration');
		$this->createForeign('Stage_championshipId', Stage::tableName(), 'championshipId', Championship::tableName(), 'id');
		$this->createForeign('Stage_cityId', Stage::tableName(), 'cityId', City::tableName(), 'id');
		$this->createForeign('Stage_class', Stage::tableName(), 'class', AthletesClass::tableName(), 'id');
		$this->createForeign('Stage_regionId', Stage::tableName(), 'regionId', Region::tableName(), 'id');
		$this->createForeign('Stage_countryId', Stage::tableName(), 'countryId', Country::tableName(), 'id');
		
		$this->createForeign('Time_participantId', Time::tableName(), 'participantId', Participant::tableName(), 'id');
		$this->createForeign('Time_stageId', Time::tableName(), 'stageId', Stage::tableName(), 'id');
		
		$this->createIndex('TmpAthlete_status', TmpAthlete::tableName(), 'status');
		$this->createIndex('TmpFigureResult_isNew', TmpFigureResult::tableName(), 'isNew');
		$this->createIndex('TmpParticipant_status', TmpParticipant::tableName(), 'status');
		
		$transaction->commit();
	}
	
	private function createIndex($name, $table, $column)
	{
		$command = \Yii::$app->db->createCommand();
		$command->createIndex($name, $table, $column);
		
		try {
			$command->execute();
		} catch (\Throwable $ex) {
			var_dump($ex->getMessage());
		}
	}
	
	private function createForeign($name, $table, $column, $refTable, $refColumn)
	{
		$command = \Yii::$app->db->createCommand();
		$command->addForeignKey($name, $table, $column, $refTable, $refColumn);
		
		try {
			$command->execute();
		} catch (\Throwable $ex) {
			var_dump($ex->getMessage());
		}
	}
	
	public function actionInsertMoscowPoints()
	{
		$array1 = [
			'A'  => [
				1 => 500,
				2 => 490,
				3 => 480
			],
			'B'  => [
				1 => 470,
				2 => 460,
				3 => 455
			],
			'D4' => [
				1  => 20,
				2  => 17,
				3  => 15,
				4  => 13,
				5  => 11,
				6  => 9,
				7  => 7,
				8  => 5,
				9  => 3,
				10 => 1
			]
		];
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($array1 as $letter => $items) {
			$class = AthletesClass::findOne(['title' => $letter]);
			if (!$class) {
				echo 'Class ' . $letter . ' not found' . PHP_EOL;
				$transaction->rollBack();
				
				return false;
			}
			foreach ($items as $place => $item) {
				$pointModel = new MoscowPoint();
				$pointModel->class = $class->id;
				$pointModel->place = $place;
				$pointModel->point = $item;
				if (!$pointModel->save()) {
					var_dump($pointModel->errors);
					$transaction->rollBack();
					
					return false;
				}
			}
		}
		//начальное количестово; смещение; количество мест
		$array2 = [
			'C1' => [
				450, 10, 5
			],
			'C2' => [
				400, 10, 5
			],
			'C3' => [
				350, 10, 5
			],
			'D1' => [
				300, 10, 10
			],
			'D2' => [
				200, 5, 20
			],
			'D3' => [
				100, 4, 20
			],
		];
		foreach ($array2 as $letter => $item) {
			$class = AthletesClass::findOne(['title' => $letter]);
			if (!$class) {
				echo 'Class ' . $letter . ' not found' . PHP_EOL;
				$transaction->rollBack();
				
				return false;
			}
			$point = $item[0];
			$place = 1;
			while ($place <= $item[2]) {
				$pointModel = new MoscowPoint();
				$pointModel->class = $class->id;
				$pointModel->place = $place;
				$pointModel->point = $point;
				if (!$pointModel->save()) {
					var_dump($pointModel->errors);
					$transaction->rollBack();
					
					return false;
				}
				$point -= $item[1];
				$place++;
			}
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionUpdateParticipants($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			echo 'Stage not found' . PHP_EOL;
			
			return false;
		}
		if ($stage->status == Stage::STATUS_PAST) {
			echo 'Past stage' . PHP_EOL;
			
			return false;
		}
		$participants = Participant::findAll(['stageId' => $stageId]);
		$count = 0;
		foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			if ($participant->athleteClassId != $athlete->athleteClassId) {
				$participant->athleteClassId = $athlete->athleteClassId;
				if (!$participant->save()) {
					var_dump($participant->errors);
					
					return false;
				}
				$count++;
			}
		}
		echo 'Update ' . $count . ' items' . PHP_EOL;
		
		return true;
	}
	
	public function actionMergeAthletes($id1, $id2)
	{
		$athlete1 = Athlete::findOne($id1);
		$athlete2 = Athlete::findOne($id2);
		if ($athlete1->hasAccount && $athlete2->hasAccount) {
			echo 'account error' . PHP_EOL;
			
			return false;
		}
		if ($athlete1->lastName != $athlete2->lastName) {
			echo 'lastname error' . PHP_EOL;
			
			return false;
		}
		if ($athlete1->hasAccount) {
			$mainAthlete = $athlete1;
		} else {
			$mainAthlete = $athlete2;
			$athlete2 = $athlete1;
		}
		
		
		$transaction = \Yii::$app->db->beginTransaction();
		$count = FigureTime::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update FigureTime: ' . $count . PHP_EOL;
		$count = Motorcycle::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update Motorcycle: ' . $count . PHP_EOL;
		$count = Notice::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update Notice: ' . $count . PHP_EOL;
		$count = Participant::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update Participant: ' . $count . PHP_EOL;
		$count = TmpAthlete::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update TmpAthlete: ' . $count . PHP_EOL;
		$count = TmpFigureResult::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update TmpFigureResult: ' . $count . PHP_EOL;
		$count = TmpParticipant::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update TmpParticipant: ' . $count . PHP_EOL;
		$count = ClassHistory::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update ClassHistory: ' . $count . PHP_EOL;
		$count = RequestForSpecialStage::updateAll(['athleteId' => $mainAthlete->id], ['athleteId' => $athlete2->id]);
		echo 'Update SpecialStages: ' . $count . PHP_EOL;
		if ($athlete2->athleteClass->percent < $mainAthlete->athleteClass->percent) {
			$mainAthlete->athleteClassId = $athlete2->athleteClassId;
			$mainAthlete->save(false);
		}
		if ($athlete2->delete()) {
			echo 'success' . PHP_EOL;
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionMergeMotorcycles($id1, $id2)
	{
		$motorcycle1 = Motorcycle::findOne($id1);
		$motorcycle2 = Motorcycle::findOne($id2);
		if ($motorcycle2->athleteId != $motorcycle1->athleteId) {
			echo 'athlete error' . PHP_EOL;
			
			return false;
		}
		$transaction = \Yii::$app->db->beginTransaction();
		$count = FigureTime::updateAll(['motorcycleId' => $motorcycle1->id], ['motorcycleId' => $motorcycle2->id]);
		echo 'Update FigureTime: ' . $count . PHP_EOL;
		$count = Participant::updateAll(['motorcycleId' => $motorcycle1->id], ['motorcycleId' => $motorcycle2->id]);
		echo 'Update Participant: ' . $count . PHP_EOL;
		$count = TmpFigureResult::updateAll(['motorcycleId' => $motorcycle1->id], ['motorcycleId' => $motorcycle2->id]);
		echo 'Update TmpFigureResult: ' . $count . PHP_EOL;
		$count = ClassHistory::updateAll(['motorcycleId' => $motorcycle1->id], ['motorcycleId' => $motorcycle2->id]);
		echo 'Update ClassHistory: ' . $count . PHP_EOL;
		
		if ($motorcycle2->delete()) {
			echo 'success' . PHP_EOL;
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionFixForStages($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			echo 'Stage not found' . PHP_EOL;
			
			return false;
		}
		$stage->referenceTime = round($stage->referenceTime / 10) * 10;
		$participants = $stage->participants;
		foreach ($participants as $participant) {
			if ($participant->percent) {
				$percent = round($participant->bestTime / $stage->referenceTime * 100, 2);
				if ($percent != $participant->percent) {
					file_put_contents('txt.txt',
						$participant->athlete->getFullName() . ': old ' .
						$participant->percent . ', new ' . $percent . PHP_EOL, FILE_APPEND);
				}
			}
		}
		
		return true;
	}
	
	public function actionDownloadTranslate()
	{
		/** @var TranslateMessageSource[] $items */
		$items = TranslateMessageSource::find()->all();
		foreach ($items as $item) {
			$message = TranslateMessage::findOne(['id' => $item->id]);
			if ($message && $message->translation) {
			} else {	$res = $item->message. ';';
			$res .= PHP_EOL;
			file_put_contents('/var/www/gymkhana/admin/web/messages.csv', $res, FILE_APPEND);}
		}
		
		return true;
	}
	
	public function actionUpdatePercent($stageId)
	{
		$stage = Stage::findOne($stageId);
		if ($stage->classModel->title != Stage::CLASS_UNPERCENT) {
			echo 'Error class' . PHP_EOL;
		}
		foreach ($stage->participants as $participant) {
			$participant->percent = null;
			$participant->save(false);
		}
		
		return true;
	}
	
	public static function actionInsertTranslate()
	{
		$filePath = 'admin/web/files/Perevody.xlsx';
		
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
							$array[$i]['message'] = $cell->getFormattedValue();
							break;
						case 'C':
							$array[$i]['translate'] = $cell->getFormattedValue();
							break;
					}
				}
			}
		}
		
		$transaction = \Yii::$app->db->beginTransaction();
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$messageSource = TranslateMessageSource::findOne(['message' => $data['message']]);
			if (!$messageSource) {
				/*$messageSource = new TranslateMessageSource();
				$messageSource->category = 'app';
				$messageSource->message = $data['message'];
				$messageSource->status = 1;
				if (!$messageSource->save()) {
					var_dump($messageSource->errors);
					$transaction->rollBack();
					
					return false;
				}*/
				echo 'Not found: ' . $data['message'] . '; ' . $data['translate'] . PHP_EOL;
				continue;
			}
			$translate = TranslateMessage::findOne(['id' => $messageSource->id]);
			if (!$translate) {
				$translate = new TranslateMessage();
				$translate->id = $messageSource->id;
			}
			$translate->language = 'en-US';
			$translate->translation = $data['translate'];
			if (!$translate->save()) {
				var_dump($translate->errors);
				$transaction->rollBack();
				
				return false;
			}
		}
		$transaction->commit();
		
		return true;
	}
	
	public function actionCancelClasses()
	{
		$notCancel = [577, 549, 550, 578, 572, 590, 571, 605];
		/** @var Participant[] $participants */
		$participants = Participant::find()->where(['stageId' => 16])
			->andWhere(['not', ['newAthleteClassId' => null]])
			->andWhere(['not', ['id' => $notCancel]])
			->all();
		$count = 0;
		foreach ($participants as $participant) {
			echo $count . PHP_EOL;
			$transaction = \Yii::$app->db->beginTransaction();
			$athlete = Athlete::findOne($participant->athleteId);
			$athlete->athleteClassId = $participant->athleteClassId;
			if (!$athlete->save()) {
				$transaction->rollBack();
				var_dump($athlete->errors);
				
				return false;
			}
			$history = ClassHistory::find()->where(['athleteId'  => $athlete->id, 'oldClassId' => $participant->athleteClassId,
			                                        'newClassId' => $participant->newAthleteClassId])->one();
			$history->delete();
			if ($athlete->hasAccount) {
				$text = 'К сожалению, была допущена ошибка: по результатам 4 этапа G-Sport класс повысили только призёры. Ваш класс по-прежнему ' . $participant->athleteClass->title;
				Notice::add($athlete->id, $text);
			}
			$participant->newAthleteClassId = null;
			$participant->newAthleteClassStatus = null;
			if (!$participant->save()) {
				$transaction->rollBack();
				var_dump($participant->errors);
				
				return false;
			}
			$transaction->commit();
			$count++;
		}
		echo 'Update: ' . $count . ' items' . PHP_EOL;
		
		return true;
	}
	
	public function actionInsertForSubscriptions()
	{
		$stage = Stage::find()->where(['not', ['dateOfThe' => null]])->andWhere(['not', ['startRegistration' => null]])->one();
		if (!$stage) {
			echo 'Stage not found' . PHP_EOL;
			
			return false;
		}
		$figure = Figure::find()->one();
		if (!$figure) {
			echo 'Figure not found' . PHP_EOL;
			
			return false;
		}
		
		SubscriptionQueue::addToQueue(NewsSubscription::TYPE_STAGES, NewsSubscription::MSG_FOR_STAGE, $stage->id);
		SubscriptionQueue::addToQueue(NewsSubscription::TYPE_STAGES, NewsSubscription::MSG_FOR_REGISTRATIONS, $stage->id);
		
		SubscriptionQueue::addToQueue(NewsSubscription::TYPE_RUSSIA_RECORDS, NewsSubscription::MSG_FOR_RUSSIA_RECORDS, $figure->id);
		SubscriptionQueue::addToQueue(NewsSubscription::TYPE_WORLD_RECORDS, NewsSubscription::MSG_FOR_WORLD_RECORDS, $figure->id);
		
		return true;
	}
	
	public function actionCheckSubscriptions()
	{
		$items = SubscriptionQueue::findAll(['isActual' => 1]);
		$countLetters = 0;
		foreach ($items as $item) {
			$count = NewsSubscription::sendMsg($item->messageType, $item->modelId);
			$item->countEmails = $count;
			$item->dateSend = time();
			$item->isActual = 0;
			$item->save();
			$countLetters += $count;
		}
		
		echo 'send ' . $countLetters . ' letters' . PHP_EOL;
		
		return true;
	}
	
	public function actionTestForStageSubscr($email)
	{
		$stage = Stage::find()->where(['not', ['startRegistration' => null]])->one();
		if (!$stage) {
			echo 'Stage not found' . PHP_EOL;
		}
		\Yii::$app->mailer->compose('subscriptions/_content', ['msgType' => NewsSubscription::MSG_FOR_REGISTRATIONS,
		                                                       'model'   => $stage, 'token' => 'test'])
			->setTo($email)
			->setFrom(['newsletter@gymkhana-cup.ru' => 'GymkhanaCup'])
			->setSubject('gymkhana-cup: тест рассылки')
			->send();
		
		return true;
	}
}