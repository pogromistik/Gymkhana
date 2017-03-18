<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

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
class TmpAthlete extends BaseActiveRecord
{
	const STATUS_NEW = 0;
	const STATUS_CANCEL = 2;
	const STATUS_ACCEPT = 1;
	
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
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'athleteId'   => 'Спортсмен',
			'firstName'   => 'Имя',
			'lastName'    => 'Фамилия',
			'phone'       => 'Телефон',
			'email'       => 'Email',
			'countryId'   => 'Страна',
			'cityId'      => 'Город',
			'city'        => 'Город',
			'motorcycles' => 'Мотоциклы',
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
	
	public function getFullName()
	{
		return $this->lastName . ' ' . $this->firstName;
	}
	
	public function getCoincidences()
	{
		/** @var Athlete[] $athletes */
		$athletes = Athlete::find()->where([
			'or',
			['upper("firstName")' => mb_strtoupper($this->firstName, 'UTF-8'), 'upper("lastName")' => mb_strtoupper($this->lastName, 'UTF-8')],
			['upper("firstName")' => mb_strtoupper($this->lastName, 'UTF-8'), 'upper("lastName")' => mb_strtoupper($this->firstName, 'UTF-8')]
		])->all();
		if (!$athletes) {
			return null;
		}
		$result = [];
		foreach ($athletes as $athlete) {
			$result[$athlete->id] = [
				'athlete'           => $athlete,
				'hasAllMotorcycles' => true,
			];
			
			$athleteMotorcycles = $this->getMotorcycles();
			foreach ($athleteMotorcycles as $motorcycle) {
				$has = $athlete->getMotorcycles()
					->andWhere(['or',
						['and', ['upper("mark")' => mb_strtoupper($motorcycle['mark'])], ['upper("model")' => mb_strtoupper($motorcycle['model'])]],
						['and', ['upper("model")' => mb_strtoupper($motorcycle['mark'])], ['upper("mark")' => mb_strtoupper($motorcycle['model'])]],
					])->one();
				if (!$has) {
					$result[$athlete->id]['hasAllMotorcycles'] = false;
					break;
				}
			}
		}
		
		return $result;
	}
}
