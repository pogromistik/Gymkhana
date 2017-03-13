<?php
namespace champ\controllers;

use common\models\Participant;
use common\models\Stage;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class ExportController extends BaseController
{
	public function actionStage($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		
		/** @var \common\models\Participant[] $participants */
		$participants = $stage->getParticipants()->andWhere(['status' => Participant::STATUS_ACTIVE])
			->orderBy(['bestTime' => SORT_ASC, 'sort' => SORT_ASC, 'id' => SORT_ASC])->all();
		
		$name = $stage->title . '-результаты.xlsx';
		$xlsFile = self::getStageResult($participants);
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
			while($i++ < 6) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex+1)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			}
			$row = $rowIndex;
			$times = $participant->times;
			if ($times) {
				$attempt = 1;
				foreach ($times as $time) {
					$sheet->getCell("G" . $row)->setValue($attempt++)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
					$sheet->getCell("H" . $row)->setValueExplicit($time->time, \PHPExcel_Cell_DataType::TYPE_NUMERIC)->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
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
			while($i++ < 12) {
				$sheet->getStyleByColumnAndRow($i, $rowIndex, $i, $rowIndex+1)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
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
}
