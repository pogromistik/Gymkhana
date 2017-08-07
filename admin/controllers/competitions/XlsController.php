<?php

namespace admin\controllers\competitions;

use admin\controllers\BaseController;
use common\models\Participant;
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
		
		$sheet->getCell("A1")->setValue('Порядок выступления')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("B1")->setValue('Участник')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("C1")->setValue('Город')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("D1")->setValue('Мотоцикл')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("E1")->setValue('Класс спортсмена')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("F1")->setValue('Класс награждения')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getCell("G1")->setValue('№')->getStyle()->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$sheet->getStyle('A1:G1')->getFont()->setBold(true);
		$sheet->getStyleByColumnAndRow(0, 1, 6, 1)->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$rowIndex = 2;
		foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			$sheet->getCell("A" . $rowIndex)->setValue($participant->sort);
			$sheet->getCell("B" . $rowIndex)->setValue($athlete->getFullName());
			$sheet->getCell("C" . $rowIndex)->setValue($athlete->city->title);
			$sheet->getCell("D" . $rowIndex)->setValue($participant->motorcycle->getFullTitle());
			$sheet->getCell("E" . $rowIndex)->setValue($participant->athleteClassId ? $participant->athleteClass->title : null);
			$sheet->getCell("F" . $rowIndex)->setValue($participant->internalClassId ? $participant->internalClass->title : null);
			$sheet->getCell("G" . $rowIndex)->setValue($participant->number);
			$rowIndex++;
		}
		$sheet->getStyleByColumnAndRow(0, $rowIndex, 6, $rowIndex)->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		$col = 0;
		$rows = count($participants)+1;
		while ($col < 7) {
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
		
		$sheet->getColumnDimension('A')
			->setWidth(10);
		$sheet->getColumnDimension('E')
			->setWidth(20);
		
		$writer = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
		$writer->save($path);
		
		return $path;
	}
	
}
