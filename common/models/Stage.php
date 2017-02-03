<?php

namespace common\models;

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
 * @property string        $class
 *
 * @property AthletesClass $classModel
 * @property Championship  $championship
 * @property City          $city
 * @property Participant[] $participants
 */
class Stage extends \yii\db\ActiveRecord
{
	public $dateOfTheHuman;
	public $startRegistrationHuman;
	public $endRegistrationHuman;
	
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_START_REGISTRATION = 3;
	const STATUS_END_REGISTRATION = 4;
	const STATUS_PRESENT = 5;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING           => 'Предстоящий этап',
		self::STATUS_START_REGISTRATION => 'Открыта регистрация на этап',
		self::STATUS_END_REGISTRATION   => 'Завершена регистрация на этап',
		self::STATUS_PRESENT            => 'Текущий этап',
		self::STATUS_PAST               => 'Прошедший этап'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'stages';
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
			[['championshipId', 'title', 'cityId', 'dateAdded', 'dateUpdated'], 'required'],
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
				'countRace'
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
			'countRace'              => 'Количество заездов'
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
		}
		if ($this->endRegistrationHuman) {
			$this->endRegistration = (new \DateTime($this->endRegistrationHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		}
		
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
}
