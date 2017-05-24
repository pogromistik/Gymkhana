<?php
namespace admin\models;

use common\models\AthletesClass;
use common\models\Time;
use yii\base\Model;

class ResultTimeForm extends Model
{
	public $time;
	public $class;
	public $timeForHuman;
	public $referenceTime;
	public $referenceTimeForHuman;
	public $percent;
	public $newClass;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['timeForHuman', 'class', 'referenceTimeForHuman'], 'required'],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'timeForHuman'          => 'Время заезда',
			'class'                 => 'Класс спортсмена',
			'referenceTimeForHuman' => 'Эталонное время трассы'
		];
	}
	
	public function calculate()
	{
		list($min, $secs) = explode(':', $this->timeForHuman);
		$this->time = ($min * 60000) + $secs * 1000;
		if ($this->time > Time::FAIL_TIME) {
			$this->time = Time::FAIL_TIME;
		}
		
		list($min, $secs) = explode(':', $this->referenceTimeForHuman);
		$this->referenceTime = ($min * 60000) + $secs * 1000;
		if ($this->referenceTime > Time::FAIL_TIME) {
			$this->referenceTime = Time::FAIL_TIME;
		}
		
		$this->percent = round($this->time / $this->referenceTime * 100, 2);
		
		
		$resultClass = AthletesClass::find()->where(['>', 'percent', $this->percent])
			->orderBy(['percent' => SORT_ASC, 'title' => SORT_DESC])->one();
		if ($resultClass && $resultClass->id != $this->class) {
			$this->newClass = $resultClass->title;
		}
		
		return true;
	}
}