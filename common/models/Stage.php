<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stages".
 *
 * @property integer $id
 * @property integer $championshipId
 * @property string  $title
 * @property string  $location
 * @property integer $cityId
 * @property string  $description
 * @property integer $dateAdded
 * @property integer $dateUpdated
 * @property integer $dateOfThe
 * @property integer $startRegistration
 * @property integer $endRegistration
 * @property integer $status
 * @property string  $class
 */
class Stage extends \yii\db\ActiveRecord
{
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_START_REGISTRATION = 3;
	const STATUS_END_REGISTRATION = 4;
	const STATUS_PRESENT = 5;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING           => 'Предстоящий',
		self::STATUS_START_REGISTRATION => 'Открыта регистрация',
		self::STATUS_END_REGISTRATION   => 'Завершена регистрация',
		self::STATUS_PRESENT            => 'Текущий',
		self::STATUS_PAST               => 'Прошедший'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'stages';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['championshipId', 'title', 'cityId', 'dateAdded', 'dateUpdated'], 'required'],
			[['championshipId', 'cityId', 'dateAdded', 'dateUpdated', 'dateOfThe', 'startRegistration', 'endRegistration', 'status'], 'integer'],
			[['title', 'location', 'description', 'class'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                => 'ID',
			'championshipId'    => 'Чемпионат',
			'title'             => 'Название',
			'location'          => 'Место проведения',
			'cityId'            => 'Город проведения',
			'description'       => 'Описание',
			'dateAdded'         => 'Дата создания',
			'dateUpdated'       => 'Дата редактирования',
			'dateOfThe'         => 'Дата проведения',
			'startRegistration' => 'Начало регистрации',
			'endRegistration'   => 'Завершение регистрации',
			'status'            => 'Статус',
			'class'             => 'Класс соревнования',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
}
