<?php
namespace admin\models;
use common\models\AthletesClass;
use common\models\Time;
use yii\base\Model;

class ReferenceTimeForm extends Model
{
	public $time;
	public $class;
	public $timeForHuman;
	public $referenceTime;
	public $referenceTimeForHuman;
	
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
			'class' => 'Класс соревнования'
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
		$this->referenceTime = round($this->time / $athleteClass->coefficient);
		$min = str_pad(floor($this->referenceTime / 60000), 2, '0', STR_PAD_LEFT);
		$sec = str_pad(floor(($this->referenceTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
		$mls = str_pad(round(($this->referenceTime - $min * 60000 - $sec * 1000) / 10), 2, '0', STR_PAD_LEFT);
		$this->referenceTimeForHuman = $min . ':' . $sec . '.' . $mls;
		
		return true;
	}
}