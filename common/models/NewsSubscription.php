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
 * @property integer $type
 * @property integer $isActive
 * @property integer $dateEnd
 * @property string  $countryIds
 */
class NewsSubscription extends \yii\db\ActiveRecord
{
	const TYPE_ALL = 1;
	const TYPE_STAGES = 2;
	const TYPE_REGISTRATIONS = 3;
	const TYPE_RECORDS = 4;
	public static $typesTitle = [
		self::TYPE_ALL           => 'все новости',
		self::TYPE_STAGES        => 'о новых этапах',
		self::TYPE_REGISTRATIONS => 'об открытых регистрациях',
		self::TYPE_RECORDS       => 'о новых рекордах'
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
			[['athleteId', 'dateAdded'], 'required'],
			[['athleteId', 'dateAdded', 'type', 'dateEnd', 'isActive'], 'integer'],
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
