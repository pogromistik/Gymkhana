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
use yii\db\Query;
use yii\helpers\ArrayHelper;

class RunController extends Controller
{
	public function actionInsertTables()
	{
		$transaction = \Yii::$app->db->beginTransaction();
		$items = (new Query())->from('about_block')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new AboutBlock();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('pages')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Page();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('about_slider')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new AboutSlider();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('albums')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Album();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('cities')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new City();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('contacts')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Contacts();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('dop_pages')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new DopPage();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('files')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Files();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('groups_menu')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new GroupMenu();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('help_project')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new HelpProject();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('layouts')->all();
		foreach ($items as $block) {
			$item = new Layout();
			foreach ($item->attributes as $attribute => $value) {
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('links')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Link();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('main_menu')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MainMenu();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('main_photo')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MainPhoto();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('marshals')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Marshal();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('menu_items')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new MenuItem();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new News();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news_block')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new NewsBlock();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('news_slider')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new NewsSlider();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('regular')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Regular();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('tracks')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Track();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		
		$items = (new Query())->from('years')->orderBy(['id' => SORT_ASC])->all();
		foreach ($items as $block) {
			$item = new Year();
			foreach ($item->attributes as $attribute => $value) {
				if ($attribute == 'id') {
					continue;
				}
				$item->$attribute = $block[$attribute];
			}
			$item->save(false);
		}
		$transaction->commit();
	}
	
	public function actionCleanIncrement($tableName, $pk)
	{
		\Yii::$app->db->createCommand('SELECT setval(\'"' . $tableName . '_' . $pk . '_seq"\'::regclass, MAX("' . $pk . '")) FROM "' . $tableName . '"')->execute();
	}
	
	public function actionTest()
	{
		$time = '01:58.69';
		list($min, $secs) = explode(':', $time);
		$time = ($min * 60000) + $secs * 1000;
		echo $time . PHP_EOL;
		$referenceTime = floor($time / 1.175);
		echo $referenceTime . PHP_EOL;
		
		$min = str_pad(floor($referenceTime / 60000), 2, '0', STR_PAD_LEFT);
		echo 'min: ' . $min . PHP_EOL;
		$sec = str_pad(floor(($referenceTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
		echo 'sec: ' . $sec . PHP_EOL;
		$mls = str_pad(round(($referenceTime - $min * 60000 - $sec * 1000) / 10, 0, PHP_ROUND_HALF_DOWN), 2, '0', STR_PAD_LEFT);
		echo 'mls: ' . $mls . PHP_EOL;
		$referenceTimeHuman = $min . ':' . $sec . '.' . $mls;
		echo $referenceTimeHuman . PHP_EOL;
		
		return true;
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
							$regions[$i]='';
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
}