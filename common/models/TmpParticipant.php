<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TmpParticipants".
 *
 * @property integer $id
 * @property integer $championshipId
 * @property integer $stageId
 * @property string  $firstName
 * @property string  $lastName
 * @property string  $city
 * @property integer $cityId
 * @property string  $motorcycleMark
 * @property string  $motorcycleModel
 * @property string  $phone
 * @property integer $number
 * @property integer $dateAdded
 * @property integer $dateUpdated
 * @property integer $status
 * @property integer $athleteId
 *
 * @property City    $cityModel
 * @property Athlete $athlete
 * @property Stage   $stage
 */
class TmpParticipant extends \yii\db\ActiveRecord
{
	const STATUS_NEW = 1;
	const STATUS_PROCESSED = 2;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TmpParticipants';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['championshipId', 'stageId', 'firstName', 'lastName', 'city', 'motorcycleMark', 'motorcycleModel', 'dateAdded', 'dateUpdated'], 'required'],
			[['championshipId', 'stageId', 'cityId', 'number', 'dateAdded', 'dateUpdated', 'status', 'athleteId'], 'integer'],
			[['firstName', 'lastName', 'city', 'motorcycleMark', 'motorcycleModel', 'phone'], 'string', 'max' => 255],
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
			'firstName'       => 'Имя',
			'lastName'        => 'Фамилия',
			'city'            => 'Город',
			'cityId'          => 'Город',
			'motorcycleMark'  => 'Марка мотоцикла',
			'motorcycleModel' => 'Модель мотоцикла',
			'phone'           => 'Телефон',
			'number'          => 'Номер участника',
			'dateAdded'       => 'Дата создания',
			'dateUpdated'     => 'Дата редактирования',
			'status'          => 'Статус',
			'athleteId'       => 'Спортсмен'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		$this->firstName = HelpModel::mb_ucfirst(trim($this->firstName));
		$this->lastName = HelpModel::mb_ucfirst(trim($this->lastName));
		if ($this->cityId) {
			$this->city = $this->cityModel->title;
		}
		
		return parent::beforeValidate();
	}
	
	public function getCityModel()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getStage()
	{
		return $this->hasOne(Stage::className(), ['id' => 'stageId']);
	}
	
	public static function createForm($stageId)
	{
		$stage = Stage::findOne($stageId);
		$form = new self();
		$form->stageId = $stageId;
		$form->championshipId = $stage->championship->id;
		
		return $form;
	}
}
