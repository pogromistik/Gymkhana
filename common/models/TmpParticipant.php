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
 * @property string       $email
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
			[['championshipId', 'stageId', 'firstName', 'lastName', 'motorcycleMark', 'motorcycleModel', 'countryId',
				'dateAdded', 'dateUpdated', 'email'], 'required'],
			[['championshipId', 'stageId', 'cityId', 'number', 'dateAdded', 'dateUpdated', 'status', 'athleteId', 'countryId'], 'integer'],
			[['firstName', 'lastName', 'city', 'motorcycleMark', 'motorcycleModel', 'phone', 'email'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'              => 'ID',
			'championshipId'  => \Yii::t('app', 'Чемпионат'),
			'stageId'         => \Yii::t('app', 'Этап'),
			'firstName'       => \Yii::t('app', 'Имя'),
			'lastName'        => \Yii::t('app', 'Фамилия'),
			'city'            => \Yii::t('app', 'Город'),
			'cityId'          => \Yii::t('app', 'Город'),
			'motorcycleMark'  => \Yii::t('app', 'Марка мотоцикла'),
			'motorcycleModel' => \Yii::t('app', 'Модель мотоцикла'),
			'phone'           => \Yii::t('app', 'Телефон'),
			'number'          => \Yii::t('app', 'Номер участника'),
			'dateAdded'       => \Yii::t('app', 'Дата создания'),
			'dateUpdated'     => \Yii::t('app', 'Дата редактирования'),
			'status'          => \Yii::t('app', 'Статус'),
			'athleteId'       => \Yii::t('app', 'Спортсмен'),
			'countryId'       => \Yii::t('app', 'Страна'),
			'email'           => \Yii::t('app', 'Email')
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
			if ($this->email) {
				$this->email = trim(mb_strtolower($this->email));
			}
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
				if ((mb_strtoupper($motorcycle->mark, 'UTF-8') == mb_strtoupper($this->motorcycleMark, 'UTF-8')
						&& mb_strtoupper($motorcycle->model, 'UTF-8') == mb_strtoupper($this->motorcycleModel, 'UTF-8'))
					|| mb_strtoupper($motorcycle->mark, 'UTF-8') == mb_strtoupper($this->motorcycleModel, 'UTF-8')
					&& mb_strtoupper($motorcycle->model, 'UTF-8') == mb_strtoupper($this->motorcycleMark, 'UTF-8')
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
	
	public static function countNewReg()
	{
		$result = self::find()->where(['status' => self::STATUS_NEW]);
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			$stageIds = Stage::find()->select('id')->where(['regionId' => \Yii::$app->user->identity->regionId])
				->andWhere(['not', ['status' => Stage::STATUS_PAST]])->asArray()->column();
			$result = $result->andWhere(['stageId' => $stageIds]);
		}
		
		return $result->count();
	}
}
