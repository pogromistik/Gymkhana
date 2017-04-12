<?php

namespace common\models;

use common\components\BaseActiveRecord;
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
 * @property integer       $placeOfClass
 * @property integer       $percent
 * @property integer       $newAthleteClassId
 * @property integer       $newAthleteClassStatus
 * @property integer       $placeOfAthleteClass
 * @property integer       $points
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property InternalClass $internalClass
 * @property Time[]        $times
 * @property Stage         $stage
 * @property Championship  $championship
 * @property AthletesClass $athleteClass
 * @property AthletesClass $newAthleteClass
 */
class Participant extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $humanBestTime;
	
	const STATUS_ACTIVE = 1;
	const STATUS_DISQUALIFICATION = 2;
	const STATUS_CANCEL_ATHLETE = 3;
	const STATUS_CANCEL_ADMINISTRATION = 4;
	
	const NEW_CLASS_STATUS_NEED_CHECK = 1;
	const NEW_CLASS_STATUS_APPROVE = 2;
	const NEW_CLASS_STATUS_CANCEL = 3;
	
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
		return 'Participants';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['championshipId', 'stageId', 'athleteId', 'motorcycleId', 'dateAdded'], 'required'],
			['percent', 'number'],
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
				'status',
				'placeOfClass',
				'newAthleteClassId',
				'newAthleteClassStatus',
				'placeOfAthleteClass',
				'points'
			], 'integer'],
			['number', 'validateNumber']
		];
	}
	
	public function validateNumber($attribute, $params)
	{
		if (!$this->hasErrors() && $this->number) {
			$freeNumbers = Championship::getFreeNumbers($this->stage, $this->athleteId);
			if (!in_array($this->number, $freeNumbers)) {
				$this->addError($attribute, 'Номер занят.');
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => 'ID',
			'championshipId'      => 'Чемпионат',
			'stageId'             => 'Этап',
			'athleteId'           => 'Спортсмен',
			'motorcycleId'        => 'Мотоцикл',
			'internalClassId'     => 'Класс награждения',
			'athleteClassId'      => 'Класс спортсмена',
			'bestTime'            => 'Лучшее время',
			'place'               => 'Место в соревнованиях',
			'placeOfClass'        => 'Место в классе награждения',
			'placeOfAthleteClass' => 'Место в классе спортсмена',
			'number'              => 'Номер спортсмена',
			'sort'                => 'Сортировка',
			'dateAdded'           => 'Дата добавления',
			'status'              => 'Статус',
			'percent'             => 'Рейтинг',
			'newAthleteClassId'   => 'Класс по итогам проведенного этапа',
			'points'              => 'Баллы за этап'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			$this->athleteClassId = $this->athlete->athleteClassId;
			/** @var Participant $participationInPreviousStages */
			$participationInPreviousStages = Participant::find()
				->where(['championshipId' => $this->championshipId])->andWhere(['not', ['stageId' => $this->stageId]])
				->andWhere(['athleteId' => $this->athleteId])->andWhere(['motorcycleId' => $this->motorcycleId])
				->andWhere(['not', ['number' => null]])
				->one();
			if (!$participationInPreviousStages) {
				$participationInPreviousStages = Participant::find()
					->where(['championshipId' => $this->championshipId])->andWhere(['not', ['stageId' => $this->stageId]])
					->andWhere(['athleteId' => $this->athleteId])
					->andWhere(['not', ['number' => null]])
					->one();
			}
			if ($participationInPreviousStages) {
				$this->number = $participationInPreviousStages->number;
				if (!$this->athleteClassId && $participationInPreviousStages->athleteClassId) {
					$this->athleteClassId = $participationInPreviousStages->athleteClassId;
				}
			}
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->bestTime) {
			$min = str_pad(floor($this->bestTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->bestTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->bestTime - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->humanBestTime = $min . ':' . $sec . '.' . $mls;
		}
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		$stage = $this->stage;
		if ($stage->status == Stage::STATUS_PAST || $stage->status == Stage::STATUS_CALCULATE_RESULTS) {
			if (array_key_exists('bestTime', $changedAttributes)) {
				$stage->placesCalculate();
			}
		}
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
	
	public function getTimes()
	{
		return $this->hasMany(Time::className(), ['participantId' => 'id']);
	}
	
	public function getStage()
	{
		return $this->hasOne(Stage::className(), ['id' => 'stageId']);
	}
	
	public function getChampionship()
	{
		return $this->hasOne(Championship::className(), ['id' => 'championshipId']);
	}
	
	public static function createForm($athleteId, $stageId)
	{
		$stage = Stage::findOne($stageId);
		$form = new self();
		$form->athleteId = $athleteId;
		$form->stageId = $stageId;
		$form->championshipId = $stage->championship->id;
		
		return $form;
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public function getNewAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'newAthleteClassId']);
	}
}
