<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "TmpFiguresResults".
 *
 * @property integer    $id
 * @property integer    $athleteId
 * @property integer    $motorcycleId
 * @property integer    $figureId
 * @property integer    $date
 * @property integer    $time
 * @property integer    $fine
 * @property string     $videoLink
 * @property integer    $isNew
 * @property integer    $dateAdded
 * @property integer    $dateUpdated
 * @property integer    $figureResultId
 * @property string     $cancelReason
 *
 * @property Athlete    $athlete
 * @property Figure     $figure
 * @property Motorcycle $motorcycle
 */
class TmpFigureResult extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $timeForHuman;
	public $dateForHuman;
	
	const STATUS_NEW = 1;
	const STATUS_APPROVE = 2;
	const STATUS_CANCEL = 3;
	public static $statusesTitle = [
		self::STATUS_NEW     => 'Новые заявки',
		self::STATUS_APPROVE => 'Подтверждённые заявки',
		self::STATUS_CANCEL  => 'Отклоненные заявки'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TmpFiguresResults';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'motorcycleId', 'figureId', 'date', 'time', 'videoLink', 'dateAdded', 'dateUpdated'], 'required'],
			[['athleteId', 'motorcycleId', 'figureId', 'date', 'time', 'fine', 'isNew', 'dateAdded', 'dateUpdated', 'figureResultId'], 'integer'],
			[['videoLink', 'cancelReason'], 'string', 'max' => 255],
			[['dateForHuman', 'timeForHuman'], 'string'],
			['fine', 'default', 'value' => 0]
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
			'figureId'     => 'Фигура',
			'date'         => 'Дата заезда',
			'time'         => 'Время (без учёта штрафных секунд)',
			'dateForHuman' => 'Дата заезда',
			'timeForHuman' => 'Время (без учёта штрафных секунд)',
			'fine'         => 'Штраф',
			'videoLink'    => 'Ссылка на видео-подтверждение',
			'isNew'        => 'Is New',
			'dateAdded'    => 'Дата добавления',
			'dateUpdated'  => 'Дата редактирования',
			'cancelReason' => 'Причина отказа'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		if ($this->timeForHuman) {
			list($min, $secs) = explode(':', $this->timeForHuman);
			$this->time = ($min * 60000) + $secs * 1000;
		}
		
		if ($this->dateForHuman) {
			$this->date = (new \DateTime($this->dateForHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->time) {
			$min = str_pad(floor($this->time / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->time - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->time - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->timeForHuman = $min . ':' . $sec . '.' . $mls;
		}
		
		if ($this->date) {
			$this->dateForHuman = date('d.m.Y', $this->date);
		}
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getFigure()
	{
		return $this->hasOne(Figure::className(), ['id' => 'figureId']);
	}
	
	public function getMotorcycle()
	{
		return $this->hasOne(Motorcycle::className(), ['id' => 'motorcycleId']);
	}
}
