<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "RequestsForSpecialStages".
 *
 * @property integer       $id
 * @property string        $data
 * @property integer       $athleteId
 * @property integer       $motorcycleId
 * @property integer       $status
 * @property integer       $time
 * @property integer       $fine
 * @property integer       $resultTime
 * @property integer       $athleteClassId
 * @property integer       $newAthleteClassId
 * @property integer       $newAthleteClassStatus
 * @property integer       $percent
 * @property string        $videoLink
 * @property string        $cancelReason
 * @property integer       $stageId
 * @property integer       $date
 * @property integer       $dateAdded
 * @property integer       $dateUpdated
 * @property integer       $cityId
 * @property integer       $countryId
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property SpecialStage  $stage
 * @property AthletesClass $athleteClass
 * @property City          $city
 */
class RequestForSpecialStage extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $dateHuman;
	public $resultTimeHuman;
	public $timeHuman;
	
	const STATUS_NEED_CHECK = 1;
	const STATUS_APPROVE = 2;
	const STATUS_IN_ACTIVE = 3;
	const STATUS_CANCEL = 4;
	
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
			[['athleteId', 'motorcycleId', 'status', 'time', 'fine', 'resultTime', 'athleteClassId',
				'newAthleteClassId', 'newAthleteClassStatus', 'percent', 'stageId', 'date', 'dateAdded', 'dateUpdated',
				'cityId', 'countryId'], 'integer'],
			[['time', 'resultTime', 'videoLink', 'stageId', 'date', 'dateAdded', 'dateUpdated', 'countryId'], 'required'],
			['fine', 'default', 'value' => 0]
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
			'time'                  => 'Время (без учёта штрафа)',
			'timeHuman'             => 'Время (без учёта штрафа)',
			'fine'                  => 'Штраф',
			'resultTime'            => 'Итоговое время',
			'resultTimeHuman'       => 'Итоговое время',
			'athleteClassId'        => 'Класс участника',
			'newAthleteClassId'     => 'Новый класс',
			'newAthleteClassStatus' => 'New Athlete Class Status',
			'percent'               => 'Рейтинг',
			'videoLink'             => 'Ссылка на видео',
			'cancelReason'          => 'Причина отказа',
			'stageId'               => 'Этап',
			'date'                  => 'Дата заезда',
			'dateHuman'             => 'Дата заезда',
			'dateAdded'             => 'Date Added',
			'dateUpdated'           => 'Date Updated',
			'cityId'                => 'Город'
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
		if ($this->athleteId) {
			$athlete = Athlete::findOne($this->athleteId);
			if (!$this->athleteClassId) {
				$this->athleteClassId = $athlete->athleteClassId;
			}
			$this->countryId = $athlete->countryId;
			if (!$this->cityId) {
				$this->cityId = $athlete->cityId;
			}
		}
		if ($this->cityId && !$this->countryId) {
			$this->countryId = City::findOne($this->cityId)->countryId;
		}
		$this->dateUpdated = time();
		if ($this->timeHuman) {
			$this->time = HelpModel::convertTime($this->timeHuman);
		}
		$this->resultTime = $this->time;
		if ($this->fine) {
			$this->resultTime += $this->fine * 1000;
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		if ($this->athleteId && $this->status == self::STATUS_APPROVE) {
			$bestOldTime = self::find()->where(['athleteId' => $this->athleteId, 'stageId' => $this->stageId]);
			if (!$this->isNewRecord) {
				$bestOldTime = $bestOldTime->andWhere(['not', ['id' => $this->id]]);
			}
			$bestOldTime = $bestOldTime->min('"resultTime"');
			if ($this->resultTime < $bestOldTime) {
				self::updateAll(['status' => self::STATUS_IN_ACTIVE], ['stageId' => $this->stageId, 'athleteId' => $this->athleteId]);
			} else {
				if ($bestOldTime && $bestOldTime != 0) {
					$this->status = self::STATUS_IN_ACTIVE;
				}
			}
		}
		
		return parent::beforeSave($insert);
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
		if ($this->date) {
			date_default_timezone_set(HelpModel::DEFAULT_TIME_ZONE);
			$this->dateHuman = date('d.m.Y', $this->date);
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
	
	public function getStage()
	{
		return $this->hasOne(SpecialStage::className(), ['id' => 'stageId']);
	}
	
	public function getCity()
	{
		return $this->cityId ? $this->hasOne(City::className(), ['id' => 'cityId']) : null;
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public static function countNewReg()
	{
		return self::find()->where(['status' => self::STATUS_NEED_CHECK])->count();
	}
	
	public function getData()
	{
		return $this->data ? json_decode($this->data, true) : null;
	}
	
	public function getCoincidences()
	{
		$data = $this->getData();
		if (!$data) {
			return null;
		}
		/** @var Athlete[] $athletes */
		$athletes = Athlete::find()->where([
			'or',
			['upper("firstName")' => mb_strtoupper($data['firstName'], 'UTF-8'),
			 'upper("lastName")'  => mb_strtoupper($data['lastName'], 'UTF-8')],
			['upper("firstName")' => mb_strtoupper($data['lastName'], 'UTF-8'),
			 'upper("lastName")'  => mb_strtoupper($data['firstName'], 'UTF-8')]
		])->all();
		if (!$athletes) {
			return null;
		}
		$result = [];
		foreach ($athletes as $athlete) {
			$result[$athlete->id] = [
				'athlete'     => $athlete,
				'motorcycles' => []
			];
			/** @var Motorcycle $motorcycle */
			foreach ($athlete->getMotorcycles()->andWhere(['status' => Motorcycle::STATUS_ACTIVE])->all() as $motorcycle) {
				$isCoincidences = false;
				if ((mb_strtoupper($motorcycle->mark, 'UTF-8') == mb_strtoupper($data['motorcycleMark'], 'UTF-8')
						&& mb_strtoupper($motorcycle->model, 'UTF-8') == mb_strtoupper($data['motorcycleModel'], 'UTF-8'))
					|| mb_strtoupper($motorcycle->mark, 'UTF-8') == mb_strtoupper($data['motorcycleModel'], 'UTF-8')
					&& mb_strtoupper($motorcycle->model, 'UTF-8') == mb_strtoupper($data['motorcycleMark'], 'UTF-8')
				) {
					$isCoincidences = true;
				}
				$result[$athlete->id]['motorcycles'][] = [
					'motorcycle'     => $motorcycle,
					'isCoincidences' => $isCoincidences
				];
			}
		}
		
		return $result;
	}
}
