<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "participants".
 *
 * @property integer       $id
 * @property integer       $championshipId
 * @property integer       $stageId
 * @property integer       $athleteId
 * @property integer       $motorcycleId
 * @property integer       $internalClassId
 * @property integer       $athleteClassId
 * @property integer       $bestTime
 * @property integer       $place
 * @property integer       $number
 * @property integer       $sort
 * @property integer       $dateAdded
 * @property integer       $status
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property InternalClass $internalClass
 */
class Participant extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DISQUALIFICATION = 2;
	const STATUS_CANCEL_ATHLETE = 3;
	const STATUS_CANCEL_ADMINISTRATION = 4;
	
	public static $statusesTitle = [
		self::STATUS_ACTIVE                => 'Заявка активна',
		self::STATUS_DISQUALIFICATION      => 'Участник дисквалифицирован',
		self::STATUS_CANCEL_ATHLETE        => 'Отменена участником',
		self::STATUS_CANCEL_ADMINISTRATION => 'Отменена администрацией'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'participants';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['championshipId', 'stageId', 'athleteId', 'motorcycleId', 'dateAdded'], 'required'],
			[[
				'championshipId',
				'stageId',
				'athleteId',
				'motorcycleId',
				'internalClassId',
				'athleteClassId',
				'bestTime',
				'place',
				'number',
				'sort',
				'dateAdded',
				'status'
			], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'              => 'ID',
			'championshipId'  => 'Чемпионат',
			'stageId'         => 'Этап',
			'athleteId'       => 'Спортсмен',
			'motorcycleId'    => 'Мотоцикл',
			'internalClassId' => 'Класс награждения',
			'athleteClassId'  => 'Класс спортсмена',
			'bestTime'        => 'Лучшее время',
			'place'           => 'Место в соревнованиях',
			'number'          => 'Номер спортсмена',
			'sort'            => 'Сортировка',
			'dateAdded'       => 'Дата добавления',
			'status'          => 'Статус',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		
		return parent::beforeValidate();
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getMotorcycle()
	{
		return $this->hasOne(Motorcycle::className(), ['id' => 'motorcycleId']);
	}
	
	public function getInternalClass()
	{
		return $this->hasOne(InternalClass::className(), ['id' => 'internalClassId']);
	}
	
	/**
	 * @param integer $attempt
	 *
	 * @return Time
	 */
	public function getTimeForm($attempt)
	{
		$form = Time::findOne(['participantId' => $this->id, 'stageId' => $this->stageId, 'attemptNumber' => $attempt]);
		if ($form) {
			return $form;
		}
		$form = new Time();
		$form->participantId = $this->id;
		$form->stageId = $this->stageId;
		
		return $form;
	}
}
