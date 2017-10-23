<?php

namespace admin\models;

use common\models\AthletesClass;
use common\models\RequestForSpecialStage;
use common\models\Time;
use common\models\TmpAthlete;
use yii\base\Model;

class MotorcycleForm extends Model
{
	public $mark;
	public $model;
	public $cbm;
	public $power;
	public $isCruiser;
	public $id;
	public $motorcycleId;
	public $mode;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['mark', 'model', 'cbm', 'power', 'id', 'motorcycleId', 'mode'], 'required'],
			[['mark', 'model'], 'string'],
			[['cbm', 'isCruiser'], 'integer'],
			[['power'], 'number'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'mark'      => 'Марка',
			'model'     => 'Модель',
			'cbm'       => 'Объём',
			'power'     => 'Мощность',
			'isCruiser' => 'Круизёр?'
		];
	}
	
	public static function getModel(array $motorcycle, $id, $motorcycleId, $mode)
	{
		$model = new self();
		$model->mark = $motorcycle['mark'];
		$model->model = $motorcycle['model'];
		$model->cbm = $motorcycle['cbm'];
		$model->power = $motorcycle['power'];
		$model->id = $id;
		$model->motorcycleId = $motorcycleId;
		if (isset($motorcycle['isCruiser']) && $motorcycle['isCruiser'] === 1) {
			$model->isCruiser = 1;
		} else {
			$model->isCruiser = 0;
		}
		$model->mode = $mode;
		
		return $model;
	}
	
	public function save()
	{
		switch ($this->mode) {
			case 'athlete':
				return $this->saveForAthlete();
				break;
			case 'stage':
				return $this->saveForStage();
				break;
		}
		
		return false;
	}
	
	private function saveForAthlete()
	{
		$item = TmpAthlete::findOne($this->id);
		$motorcycles = $item->getMotorcycles();
		if (!isset($motorcycles[$this->motorcycleId])) {
			return false;
		}
		$motorcycles[$this->motorcycleId] = [
			'mark'      => $this->mark,
			'model'     => $this->model,
			'cbm'       => $this->cbm,
			'power'     => $this->power,
			'isCruiser' => (int)$this->isCruiser
		];
		$item->motorcycles = json_encode($motorcycles);
		if ($item->save()) {
			return true;
		}
		
		return false;
	}
	
	private function saveForStage()
	{
		$item = RequestForSpecialStage::findOne($this->id);
		$data = $item->getData();
		if (!$data) {
			return false;
		}
		$data['motorcycleMark'] = $this->mark;
		$data['motorcycleModel'] = $this->model;
		$data['cbm'] = $this->cbm;
		$data['power'] = $this->power;
		$data['isCruiser'] = (int)$this->isCruiser;
		$item->data = json_encode($data);
		if ($item->save()) {
			return true;
		}
		
		return false;
	}
}