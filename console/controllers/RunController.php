<?php

namespace console\controllers;

use common\models\City;
use yii\console\Controller;

class RunController extends Controller
{
	public static function actionInsertCities()
	{
		//file_put_contents('admin/web/files/test.txt', '1');
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
				switch ($j) {
					case 'A':
						$array[$i]['title'] = $cell;
						break;
					case 'B':
						$array[$i]['region'] = $cell;
						break;
					case 'C':
						$array[$i]['federalDistrict'] = $cell;
						break;
				}
			}
		}
		
		foreach ($array as $i => $data) {
			echo $i . PHP_EOL;
			$city = City::findOne(["title" => (string)$data['title']]);
			if (!$city) {
				$city = new City();
				$city->title = (string)$data['title'];
			}
			$city->region = (string)$data['region'];
			$city->federalDistrict = (string)$data['federalDistrict'];
			if (!$city->save()) {
				var_dump($city->errors);
				
				return false;
			}
		}
		
		return true;
	}
}