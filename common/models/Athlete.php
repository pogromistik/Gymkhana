<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "athletes".
 *
 * @property integer       $id
 * @property integer       $login
 * @property string        $firstName
 * @property string        $lastName
 * @property string        $phone
 * @property string        $email
 * @property integer       $cityId
 * @property integer       $athleteClassId
 * @property integer       $number
 * @property string        $authKey
 * @property string        $passwordHash
 * @property string        $passwordResetToken
 * @property integer       $status
 * @property integer       $createdAt
 * @property integer       $updatedAt
 * @property integer       $hasAccount
 * @property integer       $lastActivityDate
 * @property Motorcycle[]  $motorcycles
 * @property AthletesClass $athleteClass
 * @property City          $city
 */
class Athlete extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'athletes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['firstName', 'lastName', 'cityId', 'createdAt', 'updatedAt'], 'required'],
			[['login', 'cityId', 'athleteClassId', 'number', 'status', 'createdAt', 'updatedAt', 'hasAccount', 'lastActivityDate'], 'integer'],
			[['firstName', 'lastName', 'phone', 'email', 'passwordHash', 'passwordResetToken'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['number'], 'unique'],
			[['passwordResetToken'], 'unique'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'login'              => 'Логин',
			'firstName'          => 'Имя',
			'lastName'           => 'Фамилия',
			'phone'              => 'Телефон',
			'email'              => 'Почта',
			'cityId'             => 'Город',
			'athleteClassId'     => 'Класс',
			'number'             => 'Номер',
			'authKey'            => 'Auth Key',
			'passwordHash'       => 'Password Hash',
			'passwordResetToken' => 'Password Reset Token',
			'status'             => 'Статус',
			'createdAt'          => 'Создан',
			'updatedAt'          => 'Обновлен',
			'hasAccount'         => 'Аккаунт создан?',
			'lastActivityDate'   => 'Дата последней активности',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->createdAt = time();
		}
		$this->updatedAt = time();
		$this->firstName = HelpModel::mb_ucfirst($this->firstName);
		$this->lastName = HelpModel::mb_ucfirst($this->lastName);
		
		return parent::beforeValidate();
	}
	
	public function getMotorcycles()
	{
		return $this->hasMany(Motorcycle::className(), ['athleteId' => 'id']);
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public function getCity()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
	
	public static function getActiveAthletes()
	{
		$query = Athlete::find();
		$query->from([self::tableName(), Motorcycle::tableName()]);
		$query->select('Athletes.*');
		$query->andWhere(new Expression('`Athletes`.`id` = `Motorcycles`.`athleteId`'));
		return $query->all();
	}
	
	public function getFullName()
	{
		return $this->lastName . ' ' . $this->firstName;
	}
}
