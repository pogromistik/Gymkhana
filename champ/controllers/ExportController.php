<?php
namespace champ\controllers;

use common\models\Athlete;
use common\models\Championship;
use common\models\Figure;
use common\models\FigureTime;
use common\models\Participant;
use common\models\Stage;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class ExportController extends BaseController
{
	const TYPE_STAGE = 1;
	const TYPE_CHAMPIONSHIP = 2;
	const TYPE_FIGURE = 3;
	
	public function actionExport($modelId, $type, $yearId = null, $showAll = false)
	{
		$name = '';
		$xlsFile = null;
		switch ($type) {
			case self::TYPE_STAGE:
				$stage = Stage::findOne($modelId);
				if (!$stage) {
					throw new NotFoundHttpException('Этап не найден');
				}
				$participants = $stage->getParticipants()
					->andWhere(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_OUT_COMPETITION]]);
				if (Participant::find()->where(['stage' => $stage->id, 'isArrived' => 1])) {
					$participants = $participants->andWhere(['isArrived' => 1]);
				}
				$participants = $participants->orderBy(['bestTime' => SORT_ASC, 'sort' => SORT_ASC, 'id' => SORT_ASC])->all();
				$name = $stage->title . '-результаты';
				$xlsFile = self::getStageResult($participants);
				break;
			case self::TYPE_CHAMPIONSHIP:
				$championship = Championship::findOne($modelId);
				if (!$championship) {
					throw new NotFoundHttpException('Чемпионат не найден');
				}
				$stages = $championship->stages;
				$results = $results = $championship->getResults($showAll);
				$name = $championship->title . '-результаты';
				if ($showAll) {
					$name = $championship->title . '-все участники-результаты';
				}
				$xlsFile = self::getChampionshipResult($results, $stages);
				break;
			case self::TYPE_FIGURE:
				$figure = Figure::findOne($modelId);
				if (!$figure) {
					throw new NotFoundHttpException('Фигура не найдена');
				}
				$results = $figure->getResults();
				if ($yearId) {
					$results = $results->andWhere(['yearId' => $yearId]);
				}
				$results = $results
					->orderBy(['yearId' => SORT_DESC, 'resultTime' => SORT_ASC, 'date' => SORT_DESC, 'dateAdded' => SORT_DESC])->all();
				$name = $figure->title . '-результаты';
				$xlsFile = self::getFigureResult($results);
				break;
		}
		
		$name .= '.xlsx';
		\Yii::$app->response->sendFile($xlsFile, $name);
		
		unlink($xlsFile);
		
		return true;
	}
	
	/**
	 * @param Participant[] $participants
	 *
	 * @return string
	 */
	private function getStageResult($participants)
	{
		$path = tempnam("/tmp", "acc-");
		$obj = new \PHPExcel();
		foreach ($obj->getAllSheets() as $i => $sheet) {
			$obj->removeSheetByIndex($i);
		}
		
		$sheet = $obj->createSheet();
		$sheet->setTitle('Результаты');
		
		$sheet->getCell("A1")->setValue('Место в абсолюте')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("A1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("B1")->setValue('Класс спортсмена')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("C1")->setValue('Место в классе спортсмена')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("C1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue('№')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("E1")->setValue('Участник')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("F1")->setValue('Мотоцикл')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("G1")->setValue('Попытка')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("H1")->setValue('Время')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("I1")->setValue('Штраф')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("J1")->setValue('Лучшее время')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("K1")->setValue('Класс награждения')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("L1")->setValue('Место в классе награждения')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("L1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("M1")->setValue('Рейтинг')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A1:M1')->getFont()->setBold(true);
		$sheet->getStyleByColumnAndRow(0, 1, 12, 1)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$rowIndex = 2;
		foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			$sheet->getCell("A" . $rowIndex)->setValue($participant->place);
			$sheet->getCell("B" . $rowIndex)->setValue($participant->athleteClass ? $participant->athleteClass->title : '');
			$sheet->getCell("C" . $rowIndex)->setValue($participant->placeOfAthleteClass);
			$sheet->getCell("D" . $rowIndex)->setValue($participant->number);
			$sheet->getCell("E" . $rowIndex)->setValue($athlete->getFullName());
			$sheet->getCell("F" . $rowIndex)->setValue($participant->motorcycle->getFullTitle());
			$i = 0;
			while ($i < 6) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex + 1)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$i++;
			}
			$row = $rowIndex;
			$times = $participant->times;
			if ($times) {
				$attempt = 1;
				foreach ($times as $time) {
					$sheet->getCell("G" . $row)->setValue($attempt++)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getCell("H" . $row)->setValueExplicit($time->timeForHuman)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getCell("I" . $row)->setValue($time->fine)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$row++;
					$sheet->getStyleByColumnAndRow(6, $row, 8, $row)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				}
			}
			$sheet->getCell("J" . $rowIndex)->setValue($participant->bestTime ? $participant->humanBestTime : '');
			$sheet->getCell("K" . $rowIndex)->setValue($participant->internalClassId ? $participant->internalClass->title : '');
			$sheet->getCell("L" . $rowIndex)->setValue($participant->placeOfClass);
			$sheet->getCell("M" . $rowIndex)->setValue($participant->percent . '%');
			$sheet->getStyleByColumnAndRow(0, $rowIndex, 12, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$i = 8;
			while ($i++ < 12) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex + 1)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			}
			if ($times) {
				$rowIndex += count($times);
			} else {
				$rowIndex += 1;
			}
		}
		$sheet->getStyleByColumnAndRow(0, $rowIndex, 12, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		
		$sheet->getStyle("A1:A" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("B2:B" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("C2:C" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("D2:D" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("E2:E" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("F2:F" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("G2:G" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("H2:H" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("I2:I" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("J2:J" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("K2:K" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("L2:L" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("M2:M" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$sheet->getColumnDimension('D')
			->setAutoSize(true);
		$sheet->getColumnDimension('E')
			->setAutoSize(true);
		$sheet->getColumnDimension('F')
			->setAutoSize(true);
		$sheet->getColumnDimension('G')
			->setAutoSize(true);
		$sheet->getColumnDimension('H')
			->setAutoSize(true);
		$sheet->getColumnDimension('I')
			->setAutoSize(true);
		$sheet->getColumnDimension('J')
			->setAutoSize(true);
		$sheet->getColumnDimension('K')
			->setAutoSize(true);
		$sheet->getColumnDimension('M')
			->setAutoSize(true);
		
		$sheet->getColumnDimension('A')
			->setWidth(10);
		$sheet->getColumnDimension('B')
			->setWidth(20);
		$sheet->getColumnDimension('C')
			->setWidth(15);
		$sheet->getColumnDimension('L')
			->setWidth(20);
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
	
	/**
	 * @param FigureTime[] $results
	 *
	 * @return string
	 */
	private function getFigureResult($results)
	{
		$path = tempnam("/tmp", "acc-");
		$obj = new \PHPExcel();
		foreach ($obj->getAllSheets() as $i => $sheet) {
			$obj->removeSheetByIndex($i);
		}
		
		$sheet = $obj->createSheet();
		$sheet->setTitle('Результаты');
		
		$sheet->getCell("A1")->setValue('Дата')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->setValue('Класс спортсмена')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("C1")->setValue('Город')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue('Участник')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("E1")->setValue('Мотоцикл')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("F1")->setValue('Время')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("G1")->setValue('Штраф')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("H1")->setValue('Итоговое время')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("I1")->setValue('Рейтинг')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);
		$sheet->getStyleByColumnAndRow(0, 1, 8, 1)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$rowIndex = 2;
		foreach ($results as $result) {
			$athlete = $result->athlete;
			$sheet->getCell("A" . $rowIndex)->setValue($result->dateForHuman);
			$sheet->getCell("B" . $rowIndex)->setValue($result->athleteClass ? $result->athleteClass->title : '');
			$sheet->getCell("C" . $rowIndex)->setValue($athlete->city->title);
			$sheet->getCell("D" . $rowIndex)->setValue($athlete->getFullName());
			$sheet->getCell("E" . $rowIndex)->setValue($result->motorcycle->getFullTitle());
			$sheet->getCell("F" . $rowIndex)->setValue($result->timeForHuman);
			$sheet->getCell("G" . $rowIndex)->setValue($result->fine);
			$sheet->getCell("H" . $rowIndex)->setValue($result->resultTimeForHuman);
			$sheet->getCell("I" . $rowIndex)->setValue($result->percent . '%');
			$i = 0;
			while ($i < 9) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$i++;
			}
			$sheet->getStyleByColumnAndRow(0, $rowIndex, 8, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$rowIndex++;
		}
		$sheet->getStyleByColumnAndRow(0, $rowIndex, 8, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		
		$sheet->getStyle("A1:A" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("B2:B" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("C2:C" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("D2:D" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("E2:E" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("F2:F" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("G2:G" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("H2:H" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("I2:I" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$sheet->getColumnDimension('A')
			->setAutoSize(true);
		$sheet->getColumnDimension('C')
			->setAutoSize(true);
		$sheet->getColumnDimension('D')
			->setAutoSize(true);
		$sheet->getColumnDimension('E')
			->setAutoSize(true);
		$sheet->getColumnDimension('F')
			->setAutoSize(true);
		$sheet->getColumnDimension('G')
			->setAutoSize(true);
		$sheet->getColumnDimension('H')
			->setAutoSize(true);
		$sheet->getColumnDimension('I')
			->setAutoSize(true);
		
		$sheet->getColumnDimension('B')
			->setWidth(20);
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
	
	/**
	 * @param array   $results
	 * @param Stage[] $stages
	 *
	 * @return string
	 */
	private function getChampionshipResult($results, $stages)
	{
		$path = tempnam("/tmp", "acc-");
		$obj = new \PHPExcel();
		foreach ($obj->getAllSheets() as $i => $sheet) {
			$obj->removeSheetByIndex($i);
		}
		
		$sheet = $obj->createSheet();
		$sheet->setTitle('Итоги');
		
		$sheet->getCell("A1")->setValue('Место')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->setValue('Класс спортсмена')->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("C1")->setValue('Город')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue('Спортсмен')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$col = 4;
		$place = 0;
		$prevPoints = 0;
		$prevCount = 1;
		foreach ($stages as $stage) {
			$sheet->getCellByColumnAndRow($col++, 1)->setValue($stage->title)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		}
		$sheet->getCellByColumnAndRow($col, 1)->setValue('Итого')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyleByColumnAndRow(0, 1, $col, 1)->getFont()->setBold(true);
		$sheet->getStyleByColumnAndRow(0, 1, $col, 1)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$rowIndex = 2;
		foreach ($results as $result) {
			/** @var Athlete $athlete */
			$athlete = $result['athlete'];
			if ($result['points'] > 0 && $result['points'] == $prevPoints) {
				$prevCount += 1;
			} else {
				$place += $prevCount;
				$prevCount = 1;
			}
			$prevPoints = $result['points'];
			$sheet->getCell("A" . $rowIndex)->setValue($place);
			$sheet->getCell("B" . $rowIndex)->setValue($athlete->athleteClassId ? $athlete->athleteClass->title : null);
			$sheet->getCell("C" . $rowIndex)->setValue($athlete->city->title);
			$sheet->getCell("D" . $rowIndex)->setValue($athlete->getFullName());
			$col = 4;
			foreach ($stages as $stage) {
				if (isset($result['stages'][$stage->id])) {
					$sheet->getCellByColumnAndRow($col++, $rowIndex)->setValue($result['stages'][$stage->id]);
				} else {
					$sheet->getCellByColumnAndRow($col++, $rowIndex)->setValue(0);
				}
			}
			$sheet->getCellByColumnAndRow($col, $rowIndex)->setValue($result['points']);
			$i = 0;
			while ($i <= $col) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
				$i++;
			}
			$sheet->getStyleByColumnAndRow(0, $rowIndex, $col, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$rowIndex++;
		}
		
		$count = 4 + count($stages);
		$sheet->getStyleByColumnAndRow(0, $rowIndex, $count, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		
		$i = 0;
		while ($i < $count) {
			$sheet->getStyleByColumnAndRow($i, 1, $i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			if ($i != 1) {
				$sheet->getColumnDimensionByColumn($i)
					->setAutoSize(true);
			}
			$i++;
		}
		
		$sheet->getColumnDimension('B')
			->setWidth(20);
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
}
