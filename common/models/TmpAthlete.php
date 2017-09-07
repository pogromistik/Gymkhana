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
			'athleteId'   => \Yii::t('app', 'Спортсмен'),
			'firstName'   => \Yii::t('app', 'Имя'),
			'lastName'    => \Yii::t('app', 'Фамилия'),
			'phone'       => \Yii::t('app', 'Телефон'),
			'email'       => \Yii::t('app', 'Email'),
			'countryId'   => \Yii::t('app', 'Страна'),
			'cityId'      => \Yii::t('app', 'Город'),
			'city'        => \Yii::t('app', 'Город'),
			'motorcycles' => \Yii::t('app', 'Мотоциклы'),
			'status'      => \Yii::t('app', 'Статус'),
			'dateAdded'   => 'Дата добавления',
			'dateUpdated' => 'Дата редактирования',
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
		$this->firstName = HelpModel::mb_ucfirst(trim($this->firstName));
		$this->lastName = HelpModel::mb_ucfirst(trim($this->lastName));
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
						['and', ['upper("mark")' => mb_strtoupper($motorcycle['mark'], 'UTF-8')], ['upper("model")' => mb_strtoupper($motorcycle['model'], 'UTF-8')]],
						['and', ['upper("model")' => mb_strtoupper($motorcycle['mark'], 'UTF-8')], ['upper("mark")' => mb_strtoupper($motorcycle['model'], 'UTF-8')]],
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
