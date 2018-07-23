<?php

namespace champ\controllers;


use common\models\Championship;
use common\models\SpecialChamp;
use common\models\SpecialStage;
use common\models\Stage;

class StatsController extends BaseController
{
	const TYPE_STAGE = 1;
	const TYPE_SPECIAL_STAGE = 2;
	
	public function actionIndex($yearId = null, $type = null)
	{
		$this->layout = 'full-content';
		$this->pageTitle = 'Статистика';
		
		$result = $this->getData($yearId, $type);
		
		return $this->render('index', [
			'result' => $result,
			'yearId' => $yearId,
			'type'   => $type
		]);
	}
	
	public function actionDownload($yearId = null, $type = null)
	{
		$result = $this->getData($yearId, $type);
		$xlsFile = self::getXls($result);
		$title = 'stats.xlsx';
		\Yii::$app->response->sendFile($xlsFile, $title);
		
		unlink($xlsFile);
		return true;
	}
	
	/**
	 * @param $result
	 *
	 * @return bool|string
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 */
	private function getXls($result)
	{
		$path = tempnam("/tmp", "acc-");
		$obj = new \PHPExcel();
		foreach ($obj->getAllSheets() as $i => $sheet) {
			$obj->removeSheetByIndex($i);
		}
		
		$sheet = $obj->createSheet();
		$sheet->setTitle(\Yii::t('app', 'Статистика'));
		
		$sheet->getCell("A1")->setValue(\Yii::t('app', 'ID спортсмена'))->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("B1")->setValue(\Yii::t('app', 'ФИО'))->getStyle()->getAlignment()->setWrapText(true);
		$sheet->getCell("C1")->setValue(\Yii::t('app', 'Рейтинг'))->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue(\Yii::t('app', 'ID этапа'))->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("E1")->setValue(\Yii::t('app', 'Этап'))->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("F1")->setValue(\Yii::t('app', 'ID чемпионата'))->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("G1")->setValue(\Yii::t('app', 'Чемпионат'))->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A1:G1')->getFont()->setBold(true);
		$rowIndex = 2;
		foreach ($result as $items) {
			foreach ($items as $item) {
				$sheet->getCell("A" . $rowIndex)->setValue($item['athleteId']);
				$sheet->getCell("B" . $rowIndex)->setValue($item['athleteName']);
				$sheet->getCell("C" . $rowIndex)->setValue($item['percent']);
				$sheet->getCell("D" . $rowIndex)->setValue($item['stageId']);
				$sheet->getCell("E" . $rowIndex)->setValue($item['stageTitle']);
				$sheet->getCell("F" . $rowIndex)->setValue($item['champId']);
				$sheet->getCell("G" . $rowIndex)->setValue($item['champTitle']);
				$rowIndex++;
			}
		}
		
		$sheet->getStyle("A1:A" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("B2:B" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("C2:C" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("D2:D" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("E2:E" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("F2:F" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("G2:G" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
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
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
	
	private function getData($yearId, $type)
	{
		$result = [];
		
		if (!$type || $type == self::TYPE_SPECIAL_STAGE) {
			$specialChamps = SpecialChamp::find()->where(['status' => SpecialStage::STATUS_PAST]);
			if ($yearId) {
				$specialChamps->andWhere(['yearId' => $yearId]);
			}
			$specialChamps = $specialChamps->all();
			
			/** @var SpecialChamp[] $specialChamps */
			foreach ($specialChamps as $specialChamp) {
				foreach ($specialChamp->stages as $specialStage) {
					foreach ($specialStage->activeRequests as $request) {
						if (!isset($result[$request->athleteId])) {
							$result[$request->athleteId] = [];
						}
						$result[$request->athleteId][$request->dateAdded] = [
							'type'        => self::TYPE_SPECIAL_STAGE,
							'stageId'     => $specialStage->id,
							'stageTitle'  => $specialStage->title,
							'champId'     => $specialChamp->id,
							'champTitle'  => $specialChamp->title,
							'athleteId'   => $request->athleteId,
							'athleteName' => $request->athlete->getFullName(),
							'percent'     => $request->percent
						];
						ksort($result[$request->athleteId]);
					}
				}
			}
		}
		
		if (!$type || $type == self::TYPE_STAGE) {
			$champs = Championship::find()->where(['status' => Stage::STATUS_PAST]);
			if ($yearId) {
				$champs->andWhere(['yearId' => $yearId]);
			}
			$champs = $champs->all();
			
			/** @var Championship[] $champs */
			foreach ($champs as $champ) {
				foreach ($champ->stages as $stage) {
					foreach ($stage->activeParticipants as $participant) {
						if (!isset($result[$participant->athleteId])) {
							$result[$participant->athleteId] = [];
						}
						$result[$participant->athleteId][$stage->dateOfThe] = [
							'type'        => self::TYPE_STAGE,
							'stageId'     => $stage->id,
							'stageTitle'  => $stage->title,
							'champId'     => $champ->id,
							'champTitle'  => $champ->title,
							'athleteId'   => $participant->athleteId,
							'athleteName' => $participant->athlete->getFullName(),
							'percent'     => $participant->percent
						];
						ksort($result[$participant->athleteId]);
					}
				}
			}
		}
		
		ksort($result);
		return $result;
	}
}