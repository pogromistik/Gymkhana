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
	const TYPE_RUSSIA_RECORDS = 3;
	public static $typesTitle = [
		self::TYPE_STAGES        => 'о новых этапах',
		self::TYPE_REGISTRATIONS => 'об открытых регистрациях',
		self::TYPE_WORLD_RECORDS => 'о новых мировых рекордах',
		self::TYPE_WORLD_RECORDS => 'о новых Российских рекордах',
	];
	
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
			'type'       => 'Тип',
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
}
