<?php

namespace admin\models;

use common\models\AthletesClass;
use common\models\HelpModel;
use common\models\Time;
use yii\base\Model;

class ReferenceTimeForm extends Model
{
	public $time;
	public $class;
	public $timeForHuman;
	public $referenceTime;
	public $referenceTimeForHuman;
	public $coefficient;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['timeForHuman', 'class'], 'required'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'timeForHuman' => 'Время',
			'class'        => 'Класс соревнования'
		];
	}
	
	public function calculate()
	{
		list($min, $secs) = explode(':', $this->timeForHuman);
		$this->time = ($min * 60000) + $secs * 1000;
		if ($this->time > Time::FAIL_TIME) {
			$this->time = Time::FAIL_TIME;
		}
		
		$athleteClass = AthletesClass::findOne($this->class);
		$time = floor($this->time / $athleteClass->coefficient);
		$this->referenceTime = ((int)($time / 10)) * 10;
		$this->referenceTimeForHuman = HelpModel::convertTimeToHuman($this->referenceTime);
		$this->coefficient = $athleteClass->coefficient;
		
		return true;
	}
	
	public function getClassModel()
	{
		if (!$this->class) {
			return null;
		}
		
		return AthletesClass::findOne($this->class);
	}
}