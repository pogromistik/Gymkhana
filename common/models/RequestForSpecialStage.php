<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "RequestsForSpecialStages".
 *
 * @property integer    $id
 * @property string     $data
 * @property integer    $athleteId
 * @property integer    $motorcycleId
 * @property integer    $status
 * @property integer    $time
 * @property integer    $fine
 * @property integer    $resultTime
 * @property integer    $athleteClassId
 * @property integer    $newAthleteClassId
 * @property integer    $newAthleteClassStatus
 * @property integer    $percent
 * @property string     $videoLink
 * @property string     $cancelReason
 * @property integer    $stageId
 * @property integer    $date
 * @property integer    $dateAdded
 * @property integer    $dateUpdated
 *
 * @property Athlete    $athlete
 * @property Motorcycle $motorcycle
 */
class RequestForSpecialStage extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $dateHuman;
	public $resultTimeHuman;
	public $timeHuman;
	
	const STATUS_NEED_CHECK = 1;
	const STATUS_APPROVE = 2;
	const STATUS_CANCEL = 3;
	const STATUS_IN_ACTIVE = 4;
	
	public static $statusesTitle = [
		self::STATUS_NEED_CHECK => 'Ожидает проверки',
		self::STATUS_APPROVE    => 'Подтверждён',
		self::STATUS_CANCEL     => 'Отклонён',
		self::STATUS_IN_ACTIVE  => 'Неактуален'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'RequestsForSpecialStages';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['data', 'videoLink', 'cancelReason', 'dateHuman', 'resultTimeHuman', 'timeHuman'], 'string'],
			[['athleteId', 'motorcycleId', 'status', 'time', 'fine', 'resultTime', 'athleteClassId', 'newAthleteClassId', 'newAthleteClassStatus', 'percent', 'stageId', 'date', 'dateAdded', 'dateUpdated'], 'integer'],
			[['time', 'resultTime', 'videoLink', 'stageId', 'date', 'dateAdded', 'dateUpdated'], 'required'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                    => 'ID',
			'data'                  => 'Все данные',
			'athleteId'             => 'Спортсмен',
			'motorcycleId'          => 'Мотоцикл',
			'status'                => 'Статус',
			'time'                  => 'Время',
			'timeHuman'             => 'Время',
			'fine'                  => 'Штраф',
			'resultTime'            => 'Итоговое время',
			'athleteClassId'        => 'Класс участника',
			'newAthleteClassId'     => 'Новый класс',
			'newAthleteClassStatus' => 'New Athlete Class Status',
			'percent'               => 'Рейтинг',
			'videoLink'             => 'Ссылка на видео',
			'cancelReason'          => 'Причина отказа',
			'stageId'               => 'Этап',
			'date'                  => 'Дата заезда',
			'dateAdded'             => 'Date Added',
			'dateUpdated'           => 'Date Updated',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if (!$this->date) {
				$this->date = time();
			}
		}
		$this->dateUpdated = time();
		
		if ($this->resultTimeHuman) {
			$this->time = HelpModel::convertTime($this->resultTimeHuman);
		}
		$this->resultTime = $this->time;
		if ($this->fine) {
			$this->resultTime += $this->fine * 1000;
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->resultTime) {
			$this->resultTimeHuman = HelpModel::convertTimeToHuman($this->resultTime);
		}
		if ($this->time) {
			$this->timeHuman = HelpModel::convertTimeToHuman($this->time);
		}
	}
	
	public function getAthlete()
	{
		if ($this->athleteId) {
			return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
		}
		
		return null;
	}
	
	public function getMotorcycle()
	{
		if ($this->motorcycleId) {
			return $this->hasOne(Motorcycle::className(), ['id' => 'motorcycleId']);
		}
		
		return null;
	}
}
