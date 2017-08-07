<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Participant;
use common\models\Stage;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * AdditionalController implements the CRUD actions for Point model.
 */
class XlsController extends BaseController
{
	const TYPE_ALL = 1;
	const TYPE_ACTIVE = 2;
	const TYPE_ARRIVED = 3;
	public static $typesTitle = [
		self::TYPE_ALL     => 'Все участники',
		self::TYPE_ACTIVE  => 'Все не отклоненные',
		self::TYPE_ARRIVED => 'Приехавшие на этап'
	];
	
	public function init()
	{
		parent::init();
		$this->can('competitions');
	}
	
	public function actionGetXls($type, $stageId)
	{
		if (!array_key_exists($type, self::$typesTitle)) {
			throw new NotFoundHttpException();
		}
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		
		$name = 'Список участников';
		$xlsFile = null;
		$participants = Participant::find()->andWhere(['stageId' => $stageId]);
		switch ($type) {
			case self::TYPE_ALL:
				$name .= '-все участники';
				break;
			case self::TYPE_ACTIVE:
				$participants->andWhere(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_NEED_CLARIFICATION,
					Participant::STATUS_OUT_COMPETITION]]);
				$name .= '-активные участники';
				break;
			case self::TYPE_ARRIVED:
				$participants->andWhere(['status' => Participant::STATUS_ACTIVE])->andWhere(['isArrived' => 1]);
				$name .= '-приехавшие участники';
				break;
		}
		$participants = $participants->orderBy(['sort' => SORT_ASC, 'dateRegistration' => SORT_ASC])->all();
		$name .= '.xlsx';
		$xlsFile = self::getXlsFile($participants);
		\Yii::$app->response->sendFile($xlsFile, $name);
		
		unlink($xlsFile);
		
		return true;
	}
	
	/**
	 * @param Participant[] $participants
	 *
	 * @return string
	 */
	private function getXlsFile($participants)
	{
		$path = tempnam("/tmp", "acc-");
		$obj = new \PHPExcel();
		foreach ($obj->getAllSheets() as $i => $sheet) {
			$obj->removeSheetByIndex($i);
		}
		
		$sheet = $obj->createSheet();
		$sheet->setTitle('Результаты');
		
		$sheet->getCell("A1")->setValue('ID')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("B1")->setValue('Порядок выступления')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("C1")->setValue('Участник')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue('Город')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("E1")->setValue('Мотоцикл')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("F1")->setValue('Класс спортсмена')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("G1")->setValue('Класс награждения')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("H1")->setValue('№')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A1:H1')->getFont()->setBold(true);
		$sheet->getStyleByColumnAndRow(0, 1, 7, 1)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$rowIndex = 2;
		foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			$sheet->getCell("A" . $rowIndex)->setValue($participant->id);
			$sheet->getCell("B" . $rowIndex)->setValue($participant->sort);
			$sheet->getCell("C" . $rowIndex)->setValue($athlete->getFullName());
			$sheet->getCell("D" . $rowIndex)->setValue($athlete->city->title);
			$sheet->getCell("E" . $rowIndex)->setValue($participant->motorcycle->getFullTitle());
			$sheet->getCell("F" . $rowIndex)->setValue($participant->athleteClassId ? $participant->athleteClass->title : null);
			$sheet->getCell("G" . $rowIndex)->setValue($participant->internalClassId ? $participant->internalClass->title : null);
			$sheet->getCell("H" . $rowIndex)->setValue($participant->number);
			$rowIndex++;
		}
		$sheet->getStyleByColumnAndRow(0, $rowIndex, 7, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$col = 0;
		$rows = count($participants)+1;
		while ($col < 8) {
			$sheet->getStyleByColumnAndRow($col, 1, $col, $rows)->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
			$col++;
		}
		
		$sheet->getStyle("A1:A" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("B2:B" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("C2:C" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("D2:D" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("E2:E" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("F2:F" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("G2:G" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle("H2:H" . $rowIndex)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		
		$sheet->getColumnDimension('A')
			->setAutoSize(true);
		$sheet->getColumnDimension('B')
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
		
		$sheet->getColumnDimension('A')
			->setWidth(10);
		$sheet->getColumnDimension('B')
			->setWidth(10);
		$sheet->getColumnDimension('E')
			->setWidth(20);
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
	
	public function actionStageResults($stageId)
	{
		$stage = Stage::findOne($stageId);
		if (!$stage) {
			throw new NotFoundHttpException('Этап не найден');
		}
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if ($stage->regionId != \Yii::$app->user->identity->regionId) {
				throw new ForbiddenHttpException('Доступ запрещен');
			}
		}
		$name = 'Список участников';
		$xlsFile = null;
		$participants = Participant::find()->andWhere(['stageId' => $stageId])
			->andWhere(['status' => Participant::STATUS_ACTIVE])->andWhere(['isArrived' => 1])
			->orderBy(['bestTime' => SORT_ASC, 'sort' => SORT_ASC, 'id' => SORT_ASC])->all();
		$name .= $stage->title . '-результаты';
		$name .= '.xlsx';
		$xlsFile = self::getXlsResults($participants);
		\Yii::$app->response->sendFile($xlsFile, $name);
		
		unlink($xlsFile);
		
		return true;
	}
	
	/**
	 * @param Participant[] $participants
	 *
	 * @return string
	 */
	private function getXlsResults($participants)
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
	
}
