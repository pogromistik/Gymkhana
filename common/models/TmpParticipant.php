<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "TmpParticipants".
 *
 * @property integer      $id
 * @property integer      $championshipId
 * @property integer      $stageId
 * @property string       $firstName
 * @property string       $lastName
 * @property string       $city
 * @property integer      $cityId
 * @property string       $motorcycleMark
 * @property string       $motorcycleModel
 * @property string       $phone
 * @property integer      $number
 * @property integer      $dateAdded
 * @property integer      $dateUpdated
 * @property integer      $status
 * @property integer      $athleteId
 * @property integer      $countryId
 *
 * @property City         $cityModel
 * @property Athlete      $athlete
 * @property Stage        $stage
 * @property Championship $championship
 * @property Country      $country
 */
class TmpParticipant extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
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
			[['championshipId', 'stageId', 'firstName', 'lastName', 'city', 'motorcycleMark', 'motorcycleModel', 'countryId',
				'dateAdded', 'dateUpdated'], 'required'],
			[['championshipId', 'stageId', 'cityId', 'number', 'dateAdded', 'dateUpdated', 'status', 'athleteId', 'countryId'], 'integer'],
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
			'athleteId'       => 'Спортсмен',
			'countryId'       => 'Страна'
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
	
	public function getChampionship()
	{
		return $this->hasOne(Championship::className(), ['id' => 'championshipId']);
	}
	
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'countryId']);
	}
	
	public static function createForm($stageId)
	{
		$stage = Stage::findOne($stageId);
		$form = new self();
		$form->stageId = $stageId;
		$form->championshipId = $stage->championship->id;
		
		return $form;
	}
	
	public function getFullName()
	{
		return $this->lastName . ' ' . $this->firstName;
	}
	
	public function getMotorcycleTitle()
	{
		return $this->motorcycleMark . ' ' . $this->motorcycleModel;
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
				'athlete'     => $athlete,
				'motorcycles' => [],
				'requests'    => Participant::find()->where(['athleteId' => $athlete->id, 'stageId' => $this->stageId])->all()
			];
			/** @var Motorcycle $motorcycle */
			foreach ($athlete->getMotorcycles()->andWhere(['status' => Motorcycle::STATUS_ACTIVE])->all() as $motorcycle) {
				$isCoincidences = false;
				if ((mb_strtoupper($motorcycle->mark) == mb_strtoupper($this->motorcycleMark)
						&& mb_strtoupper($motorcycle->model) == mb_strtoupper($this->motorcycleModel))
					|| mb_strtoupper($motorcycle->mark) == mb_strtoupper($this->motorcycleModel)
					&& mb_strtoupper($motorcycle->model) == mb_strtoupper($this->motorcycleMark)
				) {
					$isCoincidences = true;
				}
				$result[$athlete->id]['motorcycles'][] = [
					'motorcycle'     => $motorcycle,
					'isCoincidences' => $isCoincidences
				];
			}
		}
		
		return $result;
	}
}
