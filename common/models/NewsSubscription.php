<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "NewsSubscriptions".
 *
 * @property integer $id
 * @property integer $athleteId
 * @property string  $regionIds
 * @property integer $dateAdded
 * @property string  $types
 * @property integer $isActive
 * @property integer $dateEnd
 * @property string  $countryIds
 */
class NewsSubscription extends \yii\db\ActiveRecord
{
	const TYPE_STAGES = 1;
	const TYPE_REGISTRATIONS = 2;
	const TYPE_WORLD_RECORDS = 3;
	const TYPE_RUSSIA_RECORDS = 4;
	public static $typesTitle = [
		self::TYPE_STAGES        => 'о новых этапах',
		self::TYPE_REGISTRATIONS => 'об открытых регистрациях',
		self::TYPE_WORLD_RECORDS => 'о новых мировых рекордах',
		self::TYPE_WORLD_RECORDS => 'о новых Российских рекордах',
	];
	
	const MSG_FOR_STAGE = 1;
	const MSG_FOR_SPECIAL_STAGE = 2;
	const MSG_FOR_REGISTRATIONS = 3;
	const MSG_FOR_SPECIAL_REGISTRATIONS = 4;
	const MSG_FOR_WORLD_RECORDS = 5;
	const MSG_FOR_RUSSIA_RECORDS = 6;
	
	const IS_ACTIVE_NO = 0;
	const IS_ACTIVE_YES = 1;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'NewsSubscriptions';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'dateAdded', 'types'], 'required'],
			[['athleteId', 'dateAdded', 'dateEnd', 'isActive'], 'integer'],
			[['regionIds', 'countryIds'], 'safe'],
			[['isActive'], 'default', 'value' => 1]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'athleteId'  => 'Спортсмен',
			'regionIds'  => 'Регионы',
			'dateAdded'  => 'Дата начала подписки',
			'dateEnd'    => 'Дата окончания подписки',
			'types'      => 'Тип',
			'isActive'   => 'Активность',
			'countryIds' => 'Страны'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			$this->athleteId = \Yii::$app->user->id;
		}
		
		return parent::beforeValidate();
	}
	
	public function getTypes()
	{
		if ($this->types) {
			return json_decode($this->types, true);
		}
		
		return null;
	}
	
	public function getRegionIds()
	{
		if ($this->regionIds) {
			return json_decode($this->regionIds, true);
		}
		
		return null;
	}
	
	public function getRegions($isArray = false)
	{
		if (!$this->regionIds || is_array($this->regionIds)) {
			$regionIds = $this->regionIds;
		} else {
			$regionIds = $this->getRegionIds();
		}
		if ($regionIds && $isArray) {
			return ArrayHelper::map(
				Region::findAll(['id' => $regionIds]), 'id', 'title');
		}
		
		return [];
	}
	
	public function getCountryIds()
	{
		if ($this->countryIds) {
			return json_decode($this->countryIds, true);
		}
		
		return null;
	}
	
	public static function getEmails($type = null, $countryId = null, $regionId = null)
	{
		$query = self::find()->select('athleteId')->where(['isActive' => self::IS_ACTIVE_YES]);
		if ($type) {
			$query->andWhere(['or',
				['types' => null],
				['@>', 'types', json_encode($type)]
			]);
		}
		if ($countryId) {
			$query->andWhere(['or',
				['countryIds' => null],
				['@>', 'countryIds', json_encode($countryId)]
			]);
		}
		if ($regionId) {
			$query->andWhere(['or',
				['regionIds' => null],
				['@>', 'regionIds', json_encode($regionId)]
			]);
		}
		$athleteIds = $query->asArray()->column();
		
		if ($athleteIds) {
			return Athlete::find()->select('email')->where(['id' => $athleteIds])->asArray()->column();
		}
		
		return null;
	}
	
	public static function sendMsg($msgFor, $modelId)
	{
		$model = null;
		$layout = null;
		switch ($msgFor) {
			case self::MSG_FOR_STAGE:
				$model = Stage::findOne($modelId);
				$layout = 'stage';
				$emails = self::getEmails(self::TYPE_STAGES, $model->countryId, $model->regionId);
				$theme = 'Анонс этапа';
				break;
			case self::MSG_FOR_SPECIAL_STAGE:
				$model = SpecialStage::findOne($modelId);
				$layout = 'specialStage';
				$emails = self::getEmails(self::TYPE_STAGES);
				$theme = 'Анонс этапа';
				break;
			case self::MSG_FOR_REGISTRATIONS:
				$model = Stage::findOne($modelId);
				$layout = 'stageRegistration';
				$emails = self::getEmails(self::TYPE_REGISTRATIONS, $model->countryId, $model->regionId);
				$theme = 'Открыта регистрация на этап';
				break;
			case self::MSG_FOR_SPECIAL_REGISTRATIONS:
				$model = SpecialStage::findOne($modelId);
				$layout = 'specialStageRegistration';
				$emails = self::getEmails(self::TYPE_REGISTRATIONS);
				$theme = 'Открыта регистрация на этап';
				break;
			case self::MSG_FOR_WORLD_RECORDS:
				$model = Figure::findOne($modelId);
				$layout = 'worldRecord';
				$emails = self::getEmails(self::TYPE_WORLD_RECORDS);
				$theme = 'Новый мировой рекорд';
				break;
			case self::MSG_FOR_RUSSIA_RECORDS:
				$model = Figure::findOne($modelId);
				$layout = 'russiaRecord';
				$emails = self::getEmails(self::TYPE_WORLD_RECORDS, Country::RUSSIA_ID);
				$theme = 'Новый Российский рекорд';
				break;
			default:
				return null;
		}
		if (!$model || !$emails) {
			return null;
		}
		$count = 0;
		foreach ($emails as $email) {
			if (YII_ENV == 'prod') {
				\Yii::$app->mailer->compose('subscriptions/' . $layout, ['model' => $model])
					->setTo($email)
					->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
					->setSubject('gymkhana-cup: ' . $theme)
					->send();
			}
			$count++;
		}
		
		return $count;
	}
}
