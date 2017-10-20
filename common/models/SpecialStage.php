<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "SpecialStages".
 *
 * @property integer                  $id
 * @property string                   $title
 * @property integer                  $dateAdded
 * @property integer                  $dateUpdated
 * @property string                   $description
 * @property integer                  $dateStart
 * @property integer                  $dateEnd
 * @property integer                  $dateResult
 * @property integer                  $classId
 * @property integer                  $status
 * @property string                   $photoPath
 * @property integer                  $referenceTime
 * @property integer                  $outOfCompetitions
 * @property integer                  $championshipId
 * @property string                   $title_en
 * @property string                   $descr_en
 *
 * @property AthletesClass            $class
 * @property Championship             $championship
 * @property RequestForSpecialStage[] $participants
 * @property RequestForSpecialStage[] $activeRequests
 */
class SpecialStage extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $photoFile;
	
	public $dateResultHuman;
	public $dateStartHuman;
	public $dateEndHuman;
	public $referenceTimeHuman;
	
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_START = 3;
	const STATUS_CALCULATE_RESULTS = 5;
	const STATUS_PRESENT = 6;
	const STATUS_CANCEL = 7;
	
	const PHOTO_NOT_PUBLISH = 0;
	const PHOTO_PUBLISH = 1;
	
	public $withClassesCalculate = true;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING          => 'Предстоящий этап',
		self::STATUS_START             => 'Приём результатов',
		self::STATUS_CALCULATE_RESULTS => 'Подведение итогов',
		self::STATUS_PAST              => 'Прошедший этап',
		self::STATUS_CANCEL            => 'Этап отменён'
	];
	
	public static $points = [
		1  => 20,
		2  => 17,
		3  => 15,
		4  => 13,
		5  => 11,
		6  => 10,
		7  => 9,
		8  => 8,
		9  => 7,
		10 => 6,
		11 => 5,
		12 => 4,
		13 => 3,
		14 => 2,
		15 => 1
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'SpecialStages';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'dateAdded', 'dateUpdated', 'status', 'championshipId'], 'required'],
			[['dateAdded', 'dateUpdated', 'dateStart', 'dateEnd', 'dateResult', 'classId', 'status',
				'referenceTime', 'championshipId'], 'integer'],
			[['description', 'descr_en'], 'string'],
			[['title', 'photoPath', 'title_en'], 'string', 'max' => 255],
			[['dateResultHuman', 'dateStartHuman', 'dateEndHuman', 'referenceTimeHuman'], 'string', 'max' => 255],
			['photoFile', 'file', 'extensions' => 'png, jpg', 'maxFiles' => 1, 'maxSize' => 2097152,
			                      'tooBig'     => 'Размер файла не должен превышать 2MB'],
			['outOfCompetitions', 'default', 'value' => 0]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'championshipId'     => 'Чемпионат',
			'title'              => 'Название',
			'dateAdded'          => 'Date Added',
			'dateUpdated'        => 'Date Updated',
			'description'        => 'Описание',
			'dateStart'          => 'Начало приёма результатов',
			'dateEnd'            => 'Завершение приёма результатов',
			'dateResult'         => 'Дата подведения итогов',
			'dateStartHuman'     => 'Начало приёма результатов',
			'dateEndHuman'       => 'Завершение приёма результатов',
			'dateResultHuman'    => 'Дата подведения итогов',
			'classId'            => 'Класс соревнования',
			'status'             => 'Статус',
			'photoPath'          => 'Фото трассы',
			'photoFile'          => 'Фото трассы',
			'referenceTime'      => 'Эталонное время',
			'referenceTimeHuman' => 'Эталонное время',
			'outOfCompetitions'  => 'Вне зачёта',
			'title_en'           => 'Название',
			'descr_en'           => 'Описание'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		$defaultTimeZone = HelpModel::DEFAULT_TIME_ZONE;
		
		if ($this->dateResultHuman) {
			$this->dateResult = (new \DateTime($this->dateResultHuman, new \DateTimeZone($defaultTimeZone)))->setTime(6, 0, 0)->getTimestamp();
		}
		if ($this->dateStartHuman) {
			$this->dateStart = (new \DateTime($this->dateStartHuman, new \DateTimeZone($defaultTimeZone)))->getTimestamp();
		} else {
			$this->dateStart = null;
		}
		if ($this->dateEndHuman) {
			$this->dateEnd = (new \DateTime($this->dateEndHuman, new \DateTimeZone($defaultTimeZone)))->getTimestamp();
		} else {
			$this->dateEnd = null;
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'photoFile');
		if ($file && $file->size <= 2097152) {
			if ($this->photoPath) {
				HelpModel::deleteFile($this->photoPath);
			}
			$dir = \Yii::getAlias('@files') . '/' . 'stages-tracks';
			if (!file_exists($dir)) {
				mkdir($dir);
			}
			$title = uniqid() . '.' . $file->extension;
			$folder = $dir . '/' . $title;
			if ($file->saveAs($folder)) {
				$this->photoPath = 'stages-tracks/' . $title;
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterFind()
	{
		parent::afterFind();
		
		$defaultTimeZone = HelpModel::DEFAULT_TIME_ZONE;
		date_default_timezone_set($defaultTimeZone);
		if ($this->dateResult) {
			$this->dateResultHuman = date('d.m.Y', $this->dateResult);
		}
		if ($this->dateStart) {
			$this->dateStartHuman =
				date('d.m.Y, H:i', $this->dateStart);
		}
		if ($this->dateEnd) {
			$this->dateEndHuman = date('d.m.Y, H:i', $this->dateEnd);
		}
		if ($this->referenceTime) {
			$min = str_pad(floor($this->referenceTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->referenceTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(round(($this->referenceTime - $min * 60000 - $sec * 1000) / 10), 2, '0', STR_PAD_LEFT);
			$this->referenceTimeHuman = $min . ':' . $sec . '.' . $mls;
		}
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		if ($insert) {
			AssocNews::createStandardNews(AssocNews::TEMPLATE_SPECIAL_STAGE, $this);
		}
	}
	
	public function getChampionship()
	{
		return $this->hasOne(SpecialChamp::className(), ['id' => 'championshipId']);
	}
	
	public function getClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'classId']);
	}
	
	public function getParticipants()
	{
		return $this->hasMany(RequestForSpecialStage::className(), ['stageId' => 'id'])
			->orderBy(['resultTime' => SORT_ASC, 'dateAdded' => SORT_ASC]);
	}
	
	public function getActiveRequests()
	{
		return $this->hasMany(RequestForSpecialStage::className(), ['stageId' => 'id'])
			->andOnCondition(['status' => RequestForSpecialStage::STATUS_APPROVE])
			->orderBy(['resultTime' => SORT_ASC, 'dateAdded' => SORT_ASC]);
	}
	
	public function isOpen()
	{
		if ($this->dateStart) {
			if ($this->dateStart > time()) {
				return false;
			}
			if ($this->dateEnd) {
				if ($this->dateEnd < time()) {
					return false;
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	public function classCalculate()
	{
		$requests = RequestForSpecialStage::findAll(['status' => RequestForSpecialStage::STATUS_APPROVE, 'stageId' => $this->id]);
		if ($requests) {
			$classIds = RequestForSpecialStage::find()->select('athleteClassId')
				->where(['stageId' => $this->id, 'status' => RequestForSpecialStage::STATUS_APPROVE])->distinct()->asArray()->column();
			$class = null;
			while ($classIds) {
				$percent = AthletesClass::find()->where(['id' => $classIds])->min('"percent"');
				/** @var AthletesClass $presumablyClass */
				$presumablyClass = AthletesClass::find()->where(['percent' => $percent, 'id' => $classIds])
					->orderBy(['title' => SORT_ASC])->one();
				if (RequestForSpecialStage::find()
						->where(['stageId' => $this->id, 'status' => RequestForSpecialStage::STATUS_APPROVE])
						->andWhere(['athleteClassId' => $presumablyClass->id])
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
			
			$this->classId = $class->id;
			if (!$this->save()) {
				return false;
			}
			
			return true;
		}
		
		return null;
	}
	
	public function placesCalculate()
	{
		$this->refresh();
		RequestForSpecialStage::updateAll(['place' => null], ['stageId' => $this->id]);
		/** @var RequestForSpecialStage[] $requests */
		$requests = $this->activeRequests;
		
		$transaction = \Yii::$app->db->beginTransaction();
		/** @var RequestForSpecialStage $best */
		$best = $this->getActiveRequests()->andWhere(['athleteClassId' => $this->classId])->orderBy(['resultTime' => SORT_ASC])->one();
		$classTitle = $this->class->title;
		if ($classTitle != Stage::CLASS_UNPERCENT) {
			if (!$best) {
				$transaction->rollBack();
				
				return 'Неправильно указан класс соревнований: нет ни одного результата в классе ' . $this->class->title;
			}
			
			$referenceTime = floor($best->resultTime / $best->athleteClass->coefficient);
			$referenceTime = ((int)($referenceTime / 10)) * 10;;
			$this->referenceTime = $referenceTime;
			if ($this->status != self::STATUS_CALCULATE_RESULTS && $this->status != Stage::STATUS_PAST) {
				$this->status = self::STATUS_CALCULATE_RESULTS;
			}
			if (!$this->save(false)) {
				$transaction->rollBack();
				
				return 'Невозможно установить эталонное время для этапа';
			}
		}
		
		/** @var RequestForSpecialStage $prevResult */
		$prevResult = null;
		$place = 1;
		foreach ($requests as $item) {
			if ($item->resultTime && $item->resultTime < 1800000) {
				$item->place = $place++;
				if ($prevResult && $prevResult->resultTime == $item->resultTime) {
					$item->place = $prevResult->place;
				}
				if ($classTitle != Stage::CLASS_UNPERCENT) {
					if ($this->classId && $this->class->title == Stage::CLASS_UNPERCENT) {
						$item->percent = null;
					} else {
						$item->percent = round($item->resultTime / $this->referenceTime * 100, 2);
					}
				}
				
				//Рассчёт класса
				if ($this->withClassesCalculate) {
					if ($this->classId && $item->newAthleteClassStatus != RequestForSpecialStage::NEW_CLASS_STATUS_APPROVE) {
						$newClassId = RequestForSpecialStage::getNewClass($this->class, $item);
						if ($newClassId) {
							$item->newAthleteClassId = $newClassId;
							$item->newAthleteClassStatus = RequestForSpecialStage::NEW_CLASS_STATUS_NEED_CHECK;
						} else {
							$item->newAthleteClassId = null;
							$item->newAthleteClassStatus = null;
						}
					}
				}
				
				//Баллы
				if (isset(self::$points[$item->place])) {
					$item->points = self::$points[$item->place];
				} else {
					$item->points = 0;
				}
				
				if (!$item->save()) {
					$transaction->rollBack();
					
					return $item->athlete->getFullName() . var_dump($item->errors);
				}
				$prevResult = $item;
			} elseif ($item->percent) {
				$item->percent = null;
				$item->newAthleteClassId = null;
				$item->newAthleteClassStatus = null;
				$item->point = null;
				if (!$item->save()) {
					$transaction->rollBack();
					
					return $item->athlete->getFullName() . var_dump($item->errors);
				}
			}
		}
		
		$transaction->commit();
		
		return true;
	}
	
	public function getTitle()
	{
		if (!$this->title_en) {
			return $this->title;
		}
		switch (\Yii::$app->language) {
			case TranslateMessage::LANGUAGE_EN:
				return $this->title_en;
			case TranslateMessage::LANGUAGE_RU:
				return $this->title;
			default:
				return $this->title_en;
		}
	}
	
	public function getDescr()
	{
		if (!$this->descr_en) {
			return $this->description;
		}
		switch (\Yii::$app->language) {
			case TranslateMessage::LANGUAGE_EN:
				return $this->descr_en;
			case TranslateMessage::LANGUAGE_RU:
				return $this->description;
			default:
				return $this->descr_en;
		}
	}
}
