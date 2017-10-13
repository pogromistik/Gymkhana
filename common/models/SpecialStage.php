<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "SpecialStages".
 *
 * @property integer                $id
 * @property string                 $title
 * @property integer                $dateAdded
 * @property integer                $dateUpdated
 * @property string                 $description
 * @property integer                $dateStart
 * @property integer                $dateEnd
 * @property integer                $dateResult
 * @property integer                $classId
 * @property integer                $status
 * @property string                 $photoPath
 * @property integer                $referenceTime
 * @property integer                $outOfCompetitions
 * @property integer                $championshipId
 *
 * @property AthletesClass          $class
 * @property Championship           $championship
 * @property RequestForSpecialStage $participants
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
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING          => 'Предстоящий этап',
		self::STATUS_START             => 'Приём результатов',
		self::STATUS_CALCULATE_RESULTS => 'Подведение итогов',
		self::STATUS_PAST              => 'Прошедший этап',
		self::STATUS_CANCEL            => 'Этап отменён'
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
			[['dateAdded', 'dateUpdated', 'dateStart', 'dateEnd', 'dateResult', 'classId', 'status', 'referenceTime', 'championshipId'], 'integer'],
			[['description'], 'string'],
			[['title', 'photoPath'], 'string', 'max' => 255],
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
			'outOfCompetitions'  => 'Вне зачёта'
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
		return $this->hasOne(AthletesClass::className(), ['id' => 'class']);
	}
	
	public function getParticipants()
	{
		return $this->hasMany(RequestForSpecialStage::className(), ['stageId' => 'id'])
			->orderBy(['resultTime' => SORT_ASC, 'dateAdded' => SORT_ASC]);
	}
}
