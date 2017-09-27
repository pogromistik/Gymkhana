<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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
 * @property string        $trackPhoto
 * @property integer       $trackPhotoStatus
 * @property integer       $countryId
 * @property string        $documentIds
 * @property integer       $participantsLimit
 * @property integer       $fastenClassFor
 *
 * @property AthletesClass $classModel
 * @property Championship  $championship
 * @property City          $city
 * @property Participant[] $participants
 * @property Participant[] $activeParticipants
 * @property Participant[] $outParticipants
 * @property Participant[] $participantsForRaces
 */
class Stage extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	const CLASS_UNPERCENT = 'N';
	
	public $photoFile;
	
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
	const STATUS_CANCEL = 7;
	
	const PHOTO_NOT_PUBLISH = 0;
	const PHOTO_PUBLISH = 1;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING           => 'Предстоящий этап',
		self::STATUS_START_REGISTRATION => 'Открыта регистрация на этап',
		self::STATUS_END_REGISTRATION   => 'Завершена регистрация на этап',
		self::STATUS_PRESENT            => 'Текущий этап',
		self::STATUS_CALCULATE_RESULTS  => 'Подведение итогов',
		self::STATUS_PAST               => 'Прошедший этап',
		self::STATUS_CANCEL             => 'Этап отменён'
	];
	
	const ORDER_BY_PLACES = 0;
	const ORDER_BY_ATHLETE_CLASS = 1;
	const ORDER_BY_INTERNAL_CLASS = 2;
	
	public static $orderByTitles = [
		self::ORDER_BY_PLACES         => 'По местам вне классов',
		self::ORDER_BY_ATHLETE_CLASS  => 'По местам в классе спортсменов',
		self::ORDER_BY_INTERNAL_CLASS => 'По местам в классе награждения'
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
			[['championshipId', 'title', 'cityId', 'dateAdded', 'dateUpdated', 'regionId', 'countryId'], 'required'],
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
				'regionId',
				'trackPhotoStatus',
				'countryId',
				'participantsLimit',
				'fastenClassFor'
			], 'integer'],
			[['title', 'location', 'dateOfTheHuman', 'startRegistrationHuman', 'endRegistrationHuman', 'trackPhoto'], 'string', 'max' => 255],
			[['description'], 'string'],
			[['documentIds'], 'safe'],
			[['countRace'], 'integer', 'max' => 5],
			[['countRace'], 'integer', 'min' => 1],
			[['participantsLimit'], 'integer', 'min' => 3],
			['photoFile', 'file', 'extensions' => 'png, jpg', 'maxFiles' => 1, 'maxSize' => 2097152,
			                      'tooBig'     => 'Размер файла не должен превышать 2MB']
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
			'referenceTimeHuman'     => 'Эталонное время',
			'trackPhoto'             => 'Фото трассы',
			'photoFile'              => 'Фото трассы',
			'trackPhotoStatus'       => 'Опубликовать трассу',
			'countryId'              => 'Страна',
			'documentIds'            => 'Документы',
			'participantsLimit'      => 'Допустимое количество участников',
			'fastenClassFor'         => 'Закрепить класс участников за ... дней до этапа'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		if ($this->documentIds && is_array($this->documentIds)) {
			$this->documentIds = json_encode($this->documentIds);
		}
		if (!$this->documentIds) {
			$this->documentIds = null;
		}
		
		$defaultTimeZone = HelpModel::DEFAULT_TIME_ZONE;
		$city = City::findOne($this->cityId);
		$timezone = $city->timezone;
		if ($timezone && $timezone != '') {
			$defaultTimeZone = $timezone;
		}
		
		if ($this->dateOfTheHuman) {
			$this->dateOfThe = (new \DateTime($this->dateOfTheHuman, new \DateTimeZone($defaultTimeZone)))->setTime(6, 0, 0)->getTimestamp();
		}
		if ($this->startRegistrationHuman) {
			$this->startRegistration = (new \DateTime($this->startRegistrationHuman, new \DateTimeZone($defaultTimeZone)))->getTimestamp();
		} else {
			$this->startRegistration = null;
		}
		if ($this->endRegistrationHuman) {
			$this->endRegistration = (new \DateTime($this->endRegistrationHuman, new \DateTimeZone($defaultTimeZone)))->getTimestamp();
		} else {
			$this->endRegistration = null;
		}
		$this->regionId = $city->regionId;
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'photoFile');
		if ($file && $file->size <= 2097152) {
			if ($this->trackPhoto) {
				HelpModel::deleteFile($this->trackPhoto);
			}
			$dir = \Yii::getAlias('@files') . '/' . 'stages-tracks';
			if (!file_exists($dir)) {
				mkdir($dir);
			}
			$title = uniqid() . '.' . $file->extension;
			$folder = $dir . '/' . $title;
			if ($file->saveAs($folder)) {
				$this->trackPhoto = 'stages-tracks/' . $title;
				if (!$this->trackPhotoStatus) {
					$this->trackPhotoStatus = self::PHOTO_NOT_PUBLISH;
				}
			}
		}
		if ($this->documentIds && is_array($this->documentIds)) {
			$this->documentIds = json_encode($this->documentIds);
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterFind()
	{
		parent::afterFind();
		
		$defaultTimeZone = HelpModel::DEFAULT_TIME_ZONE;
		$timezone = $this->city->timezone;
		if ($timezone && $timezone != '') {
			$defaultTimeZone = $timezone;
		}
		date_default_timezone_set($defaultTimeZone);
		if ($this->dateOfThe) {
			$this->dateOfTheHuman = date('d.m.Y', $this->dateOfThe);
		}
		if ($this->startRegistration) {
			$this->startRegistrationHuman =
				date('d.m.Y, H:i', $this->startRegistration);
		}
		if ($this->endRegistration) {
			$this->endRegistrationHuman = date('d.m.Y, H:i', $this->endRegistration);
		}
		if ($this->referenceTime) {
			$min = str_pad(floor($this->referenceTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->referenceTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(round(($this->referenceTime - $min * 60000 - $sec * 1000) / 10), 2, '0', STR_PAD_LEFT);
			$this->referenceTimeHuman = $min . ':' . $sec . '.' . $mls;
		}
		if ($this->documentIds) {
			$this->documentIds = $this->getDocumentIds();
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
	
	public function getOutParticipants()
	{
		return $this->hasMany(Participant::className(), ['stageId' => 'id'])
			->andOnCondition(['status' => Participant::STATUS_OUT_COMPETITION])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC]);
	}
	
	public function getParticipantsForRaces()
	{
		return $this->hasMany(Participant::className(), ['stageId' => 'id'])
			->andOnCondition(['status' => [Participant::STATUS_ACTIVE, Participant::STATUS_OUT_COMPETITION]])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC]);
	}
	
	public function placesCalculate()
	{
		Participant::updateAll(['place' => null, 'placeOfClass' => null, 'placeOfAthleteClass' => null], ['stageId' => $this->id]);
		/** @var Participant[] $participants */
		$participants = $this->getActiveParticipants()->select(['*',
			'row_number() over (partition by "athleteClassId" order by "bestTime" asc) "tmpPlaceInAthleteClass"',
			'row_number() over (partition by "internalClassId" order by "bestTime" asc) "tmpPlaceInInternalClass"',
			'row_number() over (order by "bestTime" asc) "tmpPlace"'])
			->orderBy(['bestTime' => SORT_ASC])->all();
		//$place = 1;
		$transaction = \Yii::$app->db->beginTransaction();
		/** @var Participant $best */
		$best = $this->getActiveParticipants()->andWhere(['athleteClassId' => $this->class])->orderBy(['bestTime' => SORT_ASC])->one();
		if ($this->classModel->percent != 1000) {
			if (!$best) {
				$transaction->rollBack();
				
				return 'Неправильно указан класс соревнований: нет ни одного результата в классе ' . $this->classModel->title;
			}
			if (!$best->bestTime || $best->bestTime == 0) {
				$transaction->rollBack();
				
				return 'Нет ни одного результата времени в классе ' . $this->classModel->title;
			}
		}
		$referenceTime = floor($best->bestTime / $best->athleteClass->coefficient);
		$referenceTime = ((int)($referenceTime / 10)) * 10;;
		$this->referenceTime = $referenceTime;
		if ($this->status != self::STATUS_CALCULATE_RESULTS && $this->status != Stage::STATUS_PAST) {
			$this->status = self::STATUS_CALCULATE_RESULTS;
		}
		if (!$this->save(false)) {
			$transaction->rollBack();
			
			return 'Невозможно установить эталонное время для этапа';
		}
		$points = ArrayHelper::map(Point::find()->all(), 'id', 'point');
		/** @var Participant $prevResult */
		$prevResult = null;
		foreach ($participants as $participant) {
			if ($participant->bestTime && $participant->bestTime < 1800000) {
				$participant->place = $participant->tmpPlace;
				$participant->placeOfClass = $participant->tmpPlaceInInternalClass;
				$participant->placeOfAthleteClass = $participant->tmpPlaceInAthleteClass;
				if ($prevResult && $prevResult->bestTime == $participant->bestTime) {
					$participant->place = $prevResult->place;
					if ($participant->internalClassId == $prevResult->internalClassId) {
						$participant->placeOfClass = $prevResult->placeOfClass;
					}
					if ($participant->athleteClassId == $prevResult->athleteClassId) {
						$participant->placeOfAthleteClass = $prevResult->placeOfAthleteClass;
					}
				}
				/*if ($prevResult && $prevResult->bestTime == $participant->bestTime) {
					$participant->place = $place-1;
					$place++;
					if ($prevResult->athleteClassId == $participant->athleteClassId) {
						$participant->placeOfAthleteClass = $this->getActiveParticipants()
							->andWhere(['athleteClassId' => $participant->athleteClassId])->max('"placeOfAthleteClass"');
					} else {
						$participant->placeOfAthleteClass = $this->getActiveParticipants()
								->andWhere(['athleteClassId' => $participant->athleteClassId])
								->andWhere(['not', ['placeOfAthleteClass' => null]])->count() + 1;
					}
					if ($prevResult->internalClassId == $participant->internalClassId) {
						$participant->placeOfClass = $this->getActiveParticipants()
							->andWhere(['internalClassId' => $participant->internalClassId])->max('"placeOfClass"');
					} else {
						$participant->placeOfClass = $this->getActiveParticipants()
							->andWhere(['internalClassId' => $participant->internalClassId])
								->andWhere(['not', ['placeOfClass' => null]])->count() + 1;
						$participant->placeOfAthleteClass = $this->getActiveParticipants()
								->andWhere(['athleteClassId' => $participant->athleteClassId])
								->andWhere(['not', ['placeOfAthleteClass' => null]])->count() + 1;
					}
				} else {
					$participant->place = $place++;
					$participant->placeOfClass = $this->getActiveParticipants()
							->andWhere(['internalClassId' => $participant->internalClassId])
							->andWhere(['not', ['placeOfClass' => null]])->count() + 1;
					$participant->placeOfAthleteClass = $this->getActiveParticipants()
							->andWhere(['athleteClassId' => $participant->athleteClassId])
							->andWhere(['not', ['placeOfAthleteClass' => null]])->count() + 1;
				}*/
				if ($this->class && $this->classModel->title == self::CLASS_UNPERCENT) {
					$participant->percent = null;
				} else {
					$participant->percent = round($participant->bestTime / $this->referenceTime * 100, 2);
				}
				
				//баллы
				if (isset($points[$participant->place]) && $participant->percent != 0) {
					$participant->points = $points[$participant->place];
				} else {
					$participant->points = 0;
				}
				
				//Рассчёт класса
				if ($this->class && $participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_APPROVE) {
					$newClassId = Participant::getNewClass($this->classModel, $participant);
					if ($newClassId) {
						$participant->newAthleteClassId = $newClassId;
						$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_NEED_CHECK;
					} else {
						$participant->newAthleteClassId = null;
						$participant->newAthleteClassStatus = null;
					}
				}
				
				if (!$participant->save()) {
					$transaction->rollBack();
					
					return $participant->athlete->getFullName() . var_dump($participant->errors);
				}
				$prevResult = $participant;
			} elseif ($participant->percent) {
				$participant->percent = null;
				$participant->newAthleteClassId = null;
				$participant->newAthleteClassStatus = null;
				$participant->points = null;
				if (!$participant->save()) {
					$transaction->rollBack();
					
					return $participant->athlete->getFullName() . var_dump($participant->errors);
				}
			}
		}
		
		//вне зачёта
		$participants = $this->getOutParticipants()->orderBy(['bestTime' => SORT_ASC])->all();
		foreach ($participants as $participant) {
			if ($participant->bestTime && $participant->bestTime < 1800000) {
				$participant->percent = round($participant->bestTime / $this->referenceTime * 100, 2);
				//Рассчёт класса
				if ($this->class && $participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_APPROVE) {
					$newClassId = Participant::getNewClass($this->classModel, $participant);
					if ($newClassId) {
						$participant->newAthleteClassId = $newClassId;
						$participant->newAthleteClassStatus = Participant::NEW_CLASS_STATUS_NEED_CHECK;
					} elseif ($participant->newAthleteClassId) {
						$participant->newAthleteClassId = null;
						$participant->newAthleteClassStatus = null;
					}
				}
				
				if (!$participant->save()) {
					$transaction->rollBack();
					
					return var_dump($participant->errors);
				}
			}
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function calculatePoints()
	{
		Participant::updateAll(['pointsByMoscow' => null], ['stageId' => $this->id]);
		/** @var Participant[] $participants */
		$participants = $this->getActiveParticipants()
			->select(['*', '(SELECT "AthletesClasses"."id"
FROM "AthletesClasses"
WHERE "AthletesClasses"."percent" > "Participants"."percent"
ORDER BY "AthletesClasses"."percent" asc, "AthletesClasses"."title" DESC
LIMIT 1) as "resultClass"',
				'row_number() over (partition BY (
				SELECT "AthletesClasses"."id"
FROM "AthletesClasses"
WHERE "AthletesClasses"."percent" > "Participants"."percent"
ORDER BY "AthletesClasses"."percent" asc, "AthletesClasses"."title" DESC
LIMIT 1) order by "bestTime" asc) n'])
			->andWhere(['not', ['bestTime' => null]])
			->orderBy(['bestTime' => SORT_ASC])
			->all();
		$points = ArrayHelper::map(MoscowPoint::find()->all(), 'place', 'point', 'class');
		/** @var AthletesClass $bestClass */
		//$bestClass = AthletesClass::find()->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])->one();
		$prev = null;
		foreach ($participants as $participant) {
			if (!$participant->resultClass) {
				continue;
			}
			if (!isset($points[$participant->resultClass])) {
				continue;
			}
			if ($prev && $participant->bestTime == $prev->bestTime) {
				$participant->n = $prev->n;
			}
			$places = $points[$participant->resultClass];
			if (!isset($places[$participant->n])) {
				/*if (!$participant->newAthleteClassId || $participant->newAthleteClassStatus != Participant::NEW_CLASS_STATUS_APPROVE) {
					continue;
				}
				if ($bestClass && $participant->resultClass == $bestClass->id) {
					continue;
				}*/
				$participant->pointsByMoscow = min($places);
			} else {
				$participant->pointsByMoscow = $places[$participant->n];
			}
			if (!$participant->save()) {
				return false;
			}
			$prev = $participant;
		}
		
		return true;
	}
	
	public function getDocumentIds()
	{
		if ($this->documentIds) {
			return json_decode($this->documentIds, true);
		}
		
		return null;
	}
	
	/**
	 * @return OverallFile[]
	 */
	public function getDocuments()
	{
		if (!$this->documentIds) {
			return null;
		}
		if (is_array($this->documentIds)) {
			$documentIds = $this->documentIds;
		} else {
			$documentIds = $this->getDocumentIds();
		}
		
		return OverallFile::find()->where(['id' => $documentIds])->orderBy(['id' => SORT_ASC])->all();
	}
	
	public function getQualificationResults()
	{
		$results = FigureTime::find()->where(['stageId' => $this->id])->orderBy(['figureId' => SORT_ASC, 'resultTime' => SORT_ASC])->all();
		if (!$results) {
			return null;
		}
		$figureIds = array_unique(ArrayHelper::getColumn($results, 'figureId'));
		$figureTitles = Figure::find()->select('title')->where(['id' => $figureIds])->asArray()->column();
		
		return ['results' => $results, 'figureIds' => $figureIds, 'figureTitles' => $figureTitles];
	}
	
	/**
	 * @return AthletesClass | null
	 */
	public function classCalculate()
	{
		$participants = Participant::findAll(['stageId' => $this->id, 'status' => [Participant::STATUS_ACTIVE], 'isArrived' => 1]);
		if ($participants) {
			$classIds = Participant::find()->select('athleteClassId')
				->where(['stageId' => $this->id, 'status' => Participant::STATUS_ACTIVE, 'isArrived' => 1])->distinct()->asArray()->column();
			$class = null;
			while ($classIds) {
				$percent = AthletesClass::find()->where(['id' => $classIds])->min('"percent"');
				/** @var AthletesClass $presumablyClass */
				$presumablyClass = AthletesClass::find()->where(['percent' => $percent, 'id' => $classIds])->orderBy(['title' => SORT_ASC])->one();
				if (Participant::find()
						->from(new Expression('Participants a, (SELECT *, rank() over (partition by "athleteId" order by "motorcycleId" asc) n
			from "Participants" WHERE "stageId"=' . $this->id . ') b'))
						->where(new Expression('n=1'))
						->andWhere(['a.stageId' => $this->id, 'a.status' => Participant::STATUS_ACTIVE, 'a.isArrived' => 1])
						->andWhere(['a.athleteClassId' => $presumablyClass->id])
						->andWhere(new Expression('"a"."id"="b"."id"'))
						->count() >= 3
				) {
					$class = $presumablyClass;
					break;
				}
				$key = array_search($presumablyClass->id, $classIds);
				unset($classIds[$key]);
			}
			if (!$class) {
				$class = AthletesClass::find()->where(['status' => AthletesClass::STATUS_ACTIVE])
					->orderBy(['percent' => SORT_DESC])->one();
			}
			return $class;
		} else {
			return null;
		}
	}
}
