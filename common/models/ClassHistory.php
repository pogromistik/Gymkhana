<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "ClassesHistory".
 *
 * @property integer       $id
 * @property integer       $athleteId
 * @property integer       $motorcycleId
 * @property integer       $oldClassId
 * @property integer       $newClassId
 * @property string        $event
 * @property integer       $time
 * @property integer       $bestTime
 * @property integer       $date
 * @property float         $percent
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property AthletesClass $oldClass
 * @property AthletesClass $newClass
 */
class ClassHistory extends BaseActiveRecord
{
	protected static $enableLogging = true;
	public $dateForHuman;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ClassesHistory';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'newClassId', 'event', 'date'], 'required'],
			[['athleteId', 'motorcycleId', 'oldClassId', 'newClassId', 'time', 'bestTime', 'date'], 'integer'],
			[['event'], 'string'],
			[['percent'], 'number']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'athleteId'    => 'Спортсмен',
			'motorcycleId' => 'Мотоцикл',
			'oldClassId'   => 'Старый класс',
			'newClassId'   => 'Новый класс',
			'event'        => 'Событие',
			'time'         => 'Время',
			'bestTime'     => 'Лучшее время',
			'percent'      => 'Процент от эталонного времени',
			'date'         => 'Дата',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->date = time();
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		if ($this->date) {
			$this->dateForHuman = date('d.m.Y', $this->date);
		}
	}
	
	public static function create($athleteId, $motorcycleId = null, $oldClassId = null, $newClassId, $event,
	                              $time = null, $bestTime = null, $percent = null)
	{
		$item = new self();
		$item->athleteId = $athleteId;
		$item->motorcycleId = $motorcycleId;
		$item->oldClassId = $oldClassId;
		$item->newClassId = $newClassId;
		$item->event = $event;
		if ($time) {
			$item->time = $time;
		}
		if ($bestTime) {
			$item->bestTime = $bestTime;
		}
		if ($percent) {
			$item->percent = $percent;
		}
		if ($item->save()) {
			return true;
		}
		
		return false;
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getMotorcycle()
	{
		return $this->hasOne(Motorcycle::className(), ['id' => 'motorcycleId']);
	}
	
	public function getOldClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'oldClassId']);
	}
	
	public function getNewClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'newClassId']);
	}
}
