<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "FigureTimes".
 *
 * @property integer $id
 * @property integer $figureId
 * @property integer $athleteId
 * @property integer $motorcycleId
 * @property integer $yearId
 * @property integer $athleteClassId
 * @property integer $newAthleteClassId
 * @property integer $newAthleteClassStatus
 * @property integer $date
 * @property double  $percent
 * @property integer $time
 * @property integer $fine
 * @property integer $dateAdded
 * @property integer $dateUpdated
 */
class FigureTime extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	const NEW_CLASS_STATUS_NEED_CHECK = 1;
	const NEW_CLASS_STATUS_APPROVE = 2;
	const NEW_CLASS_STATUS_CANCEL = 3;
	
	public $timeForHuman;
	public $dateForHuman;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'FigureTimes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['figureId', 'athleteId', 'motorcycleId', 'yearId', 'dateForHuman',
				'percent', 'timeForHuman', 'dateAdded', 'dateUpdated'], 'required'],
			[['figureId', 'athleteId', 'motorcycleId', 'yearId', 'athleteClassId',
				'newAthleteClassId', 'newAthleteClassStatus', 'date', 'time', 'fine', 'dateAdded',
				'dateUpdated'], 'integer'],
			[['dateForHuman', 'timeForHuman'], 'string'],
			[['percent'], 'number'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                    => 'ID',
			'figureId'              => 'Фигура',
			'athleteId'             => 'Спортсмен',
			'motorcycleId'          => 'Мотоцикл',
			'yearId'                => 'Год',
			'athleteClassId'        => 'Класс спортсмена',
			'newAthleteClassId'     => 'Новый класс',
			'newAthleteClassStatus' => 'New Athlete Class Status',
			'date'                  => 'Дата проезда',
			'dateForHuman'          => 'Дата проезда',
			'percent'               => 'Процент',
			'time'                  => 'Время',
			'timeForHuman'          => 'Время',
			'fine'                  => 'Штраф',
			'dateAdded'             => 'Дата добавления',
			'dateUpdated'           => 'Дата редактирования',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		if ($this->timeForHuman) {
			list($min, $secs) = explode(':', $this->timeForHuman);
			$this->time = ($min * 60000) + $secs * 1000;
		}
		if ($this->dateForHuman) {
			$this->date = (new \DateTime($this->dateForHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		}
		
		$yearTitle = date("Y", $this->date);
		$year = Year::findOne(['year' => $yearTitle]);
		if (!$year) {
			$year = new Year();
			$year->year = $yearTitle;
			$year->status = Year::STATUS_ACTIVE;
			$year->save();
		}
		$this->yearId = $year->id;
		
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->time) {
			$min = str_pad(floor($this->time / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->time - $min*60000)/1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->time-$min*60000-$sec*1000)/10, 2, '0', STR_PAD_LEFT);
			$this->timeForHuman = $min.':'.$sec.'.'.$mls;
		}
		
		if ($this->date) {
			$this->dateForHuman = date('d.m.Y', $this->date);
		}
	}
}
