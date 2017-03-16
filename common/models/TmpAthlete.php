<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TmpAthletes".
 *
 * @property integer $id
 * @property integer $athleteId
 * @property string  $firstName
 * @property string  $lastName
 * @property string  $phone
 * @property string  $email
 * @property integer $countryId
 * @property integer $cityId
 * @property string  $city
 * @property string  $motorcycles
 * @property integer $status
 * @property integer $dateAdded
 * @property integer $dateUpdated
 *
 * @property Country $country
 * @property City    $cityModel
 */
class TmpAthlete extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TmpAthletes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'countryId', 'cityId', 'status', 'dateAdded', 'dateUpdated'], 'integer'],
			[['firstName', 'lastName', 'email', 'countryId', 'motorcycles', 'dateAdded', 'dateUpdated'], 'required'],
			[['motorcycles'], 'string'],
			[['firstName', 'lastName', 'phone', 'email', 'city'], 'string', 'max' => 255],
			['email', 'email'],
			['email', 'unique'],
			['email', 'unique', 'targetClass' => Athlete::className(), 'message' => 'Спортсмен с таким e-mail уже существует.']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'athleteId'   => 'Athlete ID',
			'firstName'   => 'Имя',
			'lastName'    => 'Фамилия',
			'phone'       => 'Телефон',
			'email'       => 'Email',
			'countryId'   => 'Страна',
			'cityId'      => 'Город',
			'city'        => 'Город',
			'motorcycles' => 'Мотоцикл',
			'status'      => 'Статус',
			'dateAdded'   => 'Date Added',
			'dateUpdated' => 'Date Updated',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if (!$this->city && $this->cityId) {
				$this->city = $this->cityModel->title;
			} elseif ($this->city && $this->cityId) {
				$this->cityId = null;
			}
			$this->motorcycles = json_encode($this->motorcycles);
		}
		$this->dateUpdated = time();
		if ($this->phone) {
			$this->phone = preg_replace('~\D+~', '', $this->phone);
		}
		
		return parent::beforeValidate();
	}
	
	public function getMotorcycles()
	{
		return json_decode($this->motorcycles, true);
	}
	
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'countryId']);
	}
	
	public function getCityModel()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
}
