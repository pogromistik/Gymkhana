<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "stages".
 *
 * @property integer       $id
 * @property integer       $championshipId
 * @property string        $title
 * @property string        $location
 * @property integer       $cityId
 * @property string        $description
 * @property integer       $dateAdded
 * @property integer       $dateUpdated
 * @property integer       $dateOfThe
 * @property integer       $startRegistration
 * @property integer       $endRegistration
 * @property integer       $status
 * @property integer       $countRace
 * @property integer       $class
 * @property integer       $referenceTime
 * @property integer       $regionId
 *
 * @property AthletesClass $classModel
 * @property Championship  $championship
 * @property City          $city
 * @property Participant[] $participants
 * @property Participant[] $activeParticipants
 */
class Stage extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $dateOfTheHuman;
	public $startRegistrationHuman;
	public $endRegistrationHuman;
	public $referenceTimeHuman;
	
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_START_REGISTRATION = 3;
	const STATUS_END_REGISTRATION = 4;
	const STATUS_CALCULATE_RESULTS = 5;
	const STATUS_PRESENT = 6;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING           => 'Предстоящий этап',
		self::STATUS_START_REGISTRATION => 'Открыта регистрация на этап',
		self::STATUS_END_REGISTRATION   => 'Завершена регистрация на этап',
		self::STATUS_PRESENT            => 'Текущий этап',
		self::STATUS_CALCULATE_RESULTS  => 'Подсчёт результатов',
		self::STATUS_PAST               => 'Прошедший этап',
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Stages';
	}
	
	public function init()
	{
		parent::init();
		if ($this->isNewRecord) {
			$this->countRace = 2;
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['championshipId', 'title', 'cityId', 'dateAdded', 'dateUpdated', 'regionId'], 'required'],
			[[
				'championshipId',
				'cityId',
				'dateAdded',
				'dateUpdated',
				'dateOfThe',
				'startRegistration',
				'endRegistration',
				'status',
				'class',
				'countRace',
				'referenceTime',
				'regionId'
			], 'integer'],
			[['title', 'location', 'dateOfTheHuman', 'startRegistrationHuman', 'endRegistrationHuman'], 'string', 'max' => 255],
			['description', 'string'],
			[['countRace'], 'integer', 'max' => 5],
			[['countRace'], 'integer', 'min' => 1]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                     => 'ID',
			'championshipId'         => 'Чемпионат',
			'title'                  => 'Название',
			'location'               => 'Место проведения',
			'cityId'                 => 'Город проведения',
			'description'            => 'Описание',
			'dateAdded'              => 'Дата создания',
			'dateUpdated'            => 'Дата редактирования',
			'dateOfThe'              => 'Дата проведения',
			'dateOfTheHuman'         => 'Дата проведения',
			'startRegistration'      => 'Начало регистрации',
			'startRegistrationHuman' => 'Начало регистрации',
			'endRegistration'        => 'Завершение регистрации',
			'endRegistrationHuman'   => 'Завершение регистрации',
			'status'                 => 'Статус',
			'class'                  => 'Класс соревнования',
			'countRace'              => 'Количество заездов',
			'referenceTime'          => 'Эталонное время',
			'referenceTimeHuman'     => 'Эталонное время'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		if ($this->dateOfTheHuman) {
			$this->dateOfThe = (new \DateTime($this->dateOfTheHuman, new \DateTimeZone('GMT')))->getTimestamp();
		}
		if ($this->startRegistrationHuman) {
			$this->startRegistration = (new \DateTime($this->startRegistrationHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		} else {
			$this->startRegistration = null;
		}
		if ($this->endRegistrationHuman) {
			$this->endRegistration = (new \DateTime($this->endRegistrationHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		} else {
			$this->endRegistration = null;
		}
		$this->regionId = $this->city->regionId;
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->dateOfThe) {
			$this->dateOfTheHuman = date('d.m.Y', $this->dateOfThe);
		}
		if ($this->startRegistration) {
			$this->startRegistrationHuman = date('d.m.Y, H:i', $this->startRegistration);
		}
		if ($this->endRegistration) {
			$this->endRegistrationHuman = date('d.m.Y, H:i', $this->endRegistration);
		}
		if ($this->referenceTime) {
			$min = str_pad(floor($this->referenceTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->referenceTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(floor(($this->referenceTime - $min * 60000 - $sec * 1000) / 10), 2, '0', STR_PAD_LEFT);
			$this->referenceTimeHuman = $min . ':' . $sec . '.' . $mls;
		}
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		if ($insert) {
			AssocNews::createStandardNews(AssocNews::TEMPLATE_STAGE, $this);
		}
	}
	
	public function getChampionship()
	{
		return $this->hasOne(Championship::className(), ['id' => 'championshipId']);
	}
	
	public function getClassModel()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'class']);
	}
	
	public function getCity()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
	
	public function getParticipants()
	{
		return $this->hasMany(Participant::className(), ['stageId' => 'id'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC]);
	}
	
	public function getActiveParticipants()
	{
		return $this->hasMany(Participant::className(), ['stageId' => 'id'])
			->andOnCondition(['status' => Participant::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC]);
	}
	
	public function placesCalculate()
	{
		Participant::updateAll(['place' => null, 'placeOfClass' => null], ['stageId' => $this->id]);
		/** @var Participant[] $participants */
		$participants = $this->getActiveParticipants()->orderBy(['bestTime' => SORT_ASC])->all();
		$place = 1;
		$transaction = \Yii::$app->db->beginTransaction();
		/** @var Participant $first */
		$first = reset($participants);
		$referenceTime = floor($first->bestTime / $first->athleteClass->coefficient);
		$this->referenceTime = $referenceTime;
		if (!$this->save(false)) {
			$transaction->rollBack();
			
			return 'Невозможно установить эталонное время для этапа';
		}
		foreach ($participants as $participant) {
			$participant->place = $place++;
			$participant->placeOfClass = $this->getActiveParticipants()
					->andWhere(['internalClassId' => $participant->internalClassId])->max('"placeOfClass"') + 1;
			$participant->placeOfAthleteClass = $this->getActiveParticipants()
					->andWhere(['athleteClassId' => $participant->athleteClassId])->max('"placeOfClass"') + 1;
			$participant->percent = round($participant->bestTime / $this->referenceTime * 100, 2);
			
			//Рассчёт класса
			if ($participant->athleteClassId) {
				$participant->newAthleteClassId = null;
				$stageClass = $participant->stage->class ? $participant->stage->classModel : null;
				
				/** @var AthletesClass $newClass */
				$newClass = AthletesClass::find()->where(['>=', 'percent', $participant->percent])
					->orderBy(['percent' => SORT_ASC])->one();
				if ($stageClass && $newClass) {
					$percent = $stageClass->percent + $newClass->percent - 100;
					/** @var AthletesClass $resultClass */
					$resultClass = AthletesClass::find()->where(['>=', 'percent', $percent])
						->orderBy(['percent' => SORT_ASC])->one();
					if ($resultClass && $resultClass->id != $participant->id) {
						if ($stageClass->percent > $resultClass->percent) {
							if ($stageClass->id != $participant->athleteClassId) {
								$participant->newAthleteClassId = $stageClass->id;
								$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_NEED_CHECK;
							}
						} elseif (!$participant->athleteClassId ||
							$participant->athleteClass->percent > $resultClass->percent
						) {
							$participant->newAthleteClassId = $resultClass->id;
							$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_NEED_CHECK;
						}
					}
				}
			}
			/*if ($stageClass) {
				$offset = AthletesClass::find()->where(['<=', 'percent', $stageClass->percent])
					->andWhere(['!=', 'id', $stageClass->id])->count()-1;

				$newClass = AthletesClass::find()->where(['>=', 'percent', $participant->percent])
					->orderBy(['percent' => SORT_ASC])->one();
				if ($newClass) {
					if ($participant->id == 19) {
						//return var_dump($newClass->title);
					}
					$offset += AthletesClass::find()->where(['<=', 'percent', $newClass->percent])
						->andWhere(['!=', 'id', $newClass->id])->count();
					
					$newClass = AthletesClass::find()->offset($offset)->limit(1)->orderBy(['percent' => SORT_ASC])->one();
					if ($newClass && $newClass->id != $participant->athlete->athleteClassId) {
						if ($stageClass->percent > $newClass->percent) {
							if ($stageClass->id != $participant->athleteClassId) {
								$participant->newAthleteClassId = $stageClass->id;
							}
						} elseif (!$participant->athleteClassId ||
							$participant->athleteClass->percent > $newClass->percent
						) {
							$participant->newAthleteClassId = $newClass->id;
						}
					}
				}
			}*/
			
			if (!$participant->save()) {
				$transaction->rollBack();
				
				return var_dump($participant->errors);
			}
		}
		$transaction->commit();
		
		return true;
	}
}
