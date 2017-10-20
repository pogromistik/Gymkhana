<?php

namespace admin\models;

use common\models\AthletesClass;
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
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['mark', 'model', 'cbm', 'power', 'id', 'motorcycleId'], 'required'],
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
	
	public static function getModel(array $motorcycle, $id, $motorcycleId)
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
		
		return $model;
	}
	
	public function save()
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
}