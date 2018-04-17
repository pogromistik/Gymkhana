<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\db\Expression;

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
 * @property double        $percent
 * @property string        $videoLink
 * @property string        $cancelReason
 * @property integer       $stageId
 * @property integer       $date
 * @property integer       $dateAdded
 * @property integer       $dateUpdated
 * @property integer       $cityId
 * @property integer       $countryId
 * @property integer       $place
 * @property integer       $points
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property SpecialStage  $stage
 * @property AthletesClass $athleteClass
 * @property AthletesClass $newAthleteClass
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
	
	const NEW_CLASS_STATUS_NEED_CHECK = 1;
	const NEW_CLASS_STATUS_APPROVE = 2;
	const NEW_CLASS_STATUS_CANCEL = 3;
	
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
				'newAthleteClassId', 'newAthleteClassStatus', 'stageId', 'date', 'dateAdded', 'dateUpdated',
				'cityId', 'countryId', 'place', 'points'], 'integer'],
			[['percent'], 'number'],
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
			'athleteId'             => \Yii::t('app', 'Спортсмен'),
			'motorcycleId'          => \Yii::t('app', 'Мотоцикл'),
			'status'                => \Yii::t('app', 'Статус'),
			'time'                  => \Yii::t('app', 'Время (без учёта штрафа)'),
			'timeHuman'             => \Yii::t('app', 'Время (без учёта штрафа)'),
			'fine'                  => \Yii::t('app', 'Штраф'),
			'resultTime'            => \Yii::t('app', 'Итоговое время'),
			'resultTimeHuman'       => \Yii::t('app', 'Итоговое время'),
			'athleteClassId'        => 'Класс участника',
			'newAthleteClassId'     => 'Новый класс',
			'newAthleteClassStatus' => 'New Athlete Class Status',
			'percent'               => \Yii::t('app', 'Рейтинг'),
			'videoLink'             => \Yii::t('app', 'Ссылка на видео'),
			'cancelReason'          => \Yii::t('app', 'Причина отказа'),
			'stageId'               => \Yii::t('app', 'Этап'),
			'date'                  => \Yii::t('app', 'Дата заезда'),
			'dateHuman'             => \Yii::t('app', 'Дата заезда'),
			'dateAdded'             => 'Date Added',
			'dateUpdated'           => 'Date Updated',
			'cityId'                => \Yii::t('app', 'Город'),
			'place'                 => \Yii::t('app', 'Место'),
			'points'                => \Yii::t('app', 'Баллы')
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
			$bestOldTime = self::find()->where(['athleteId' => $this->athleteId, 'stageId' => $this->stageId])
				->andWhere(['status' => self::STATUS_APPROVE]);
			if (!$this->isNewRecord) {
				$bestOldTime = $bestOldTime->andWhere(['not', ['id' => $this->id]]);
			}
			$bestOldTime = $bestOldTime->min('"resultTime"');
			if ($this->resultTime < $bestOldTime) {
				if ($this->isNewRecord) {
					self::updateAll(['status' => self::STATUS_IN_ACTIVE], ['stageId'   => $this->stageId,
					                                                       'athleteId' => $this->athleteId,
					                                                       'status'    => self::STATUS_APPROVE
					]);
				} else {
					self::updateAll(['status' => self::STATUS_IN_ACTIVE], [
						'and',
						['stageId' => $this->stageId],
						['athleteId' => $this->athleteId],
						['status' => self::STATUS_APPROVE],
						['not', ['id' => $this->id]]
					]);
				}
			} else {
				if ($bestOldTime && $bestOldTime != 0) {
					$this->status = self::STATUS_IN_ACTIVE;
				}
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		$stage = $this->stage;
		if ($stage->dateResult && $stage->dateResult < time()) {
			if (($this->status == self::STATUS_APPROVE && array_key_exists('resultTime', $changedAttributes))
				|| array_key_exists('status', $changedAttributes)
			) {
				$stage->placesCalculate();
			}
		}
		
		if (array_key_exists('status', $changedAttributes) && $this->status != self::STATUS_APPROVE) {
			
			if (!self::find()->where(['athleteId' => $this->athleteId, 'stageId' => $this->stageId])
				->andWhere(['status' => self::STATUS_APPROVE])->one()
			) {
				$oldBest = self::find()->where(['athleteId' => $this->athleteId, 'stageId' => $this->stageId])
					->andWhere(['status' => self::STATUS_IN_ACTIVE])->andWhere(['not', ['id' => $this->id]])
					->orderBy(['resultTime' => SORT_ASC])->one();
				if ($oldBest) {
					$oldBest->status = self::STATUS_APPROVE;
					$oldBest->save();
				}
			}
		}
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
	
	public function getNewAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'newAthleteClassId']);
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
	
	public static function getNewClass(AthletesClass $stageClass, RequestForSpecialStage $request)
	{
		if ($request->athleteClassId) {
			/** @var AthletesClass $resultClass */
			$resultClass = AthletesClass::find()->where(['>', 'percent', $request->percent])
				->orderBy(['percent' => SORT_ASC, 'title' => SORT_DESC])->one();
			if ($resultClass && $resultClass->id != $request->id) {
				if ($stageClass->percent > $resultClass->percent) {
					if ($stageClass->id != $request->athleteClassId && $stageClass->percent < $request->athleteClass->percent
						&& $stageClass->id != $request->newAthleteClassId
					) {
						return $stageClass->id;
					}
				} elseif (!$request->athleteClassId ||
					$request->athleteClass->percent > $resultClass->percent && $request->newAthleteClassId != $resultClass->id
				) {
					return $resultClass->id;
				}
			}
		}
		
		return null;
	}
}
