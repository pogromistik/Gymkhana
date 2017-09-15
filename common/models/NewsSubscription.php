<?php

namespace common\models;

use Yii;

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
 */
class NewsSubscription extends \yii\db\ActiveRecord
{
	const TYPE_ALL = 1;
	const TYPE_STAGES = 2;
	const TYPE_REGISTRATIONS = 3;
	public static $typesTitle = [
		self::TYPE_ALL           => 'все новости',
		self::TYPE_STAGES        => 'о новых этапах',
		self::TYPE_REGISTRATIONS => 'об открытых регистрациях'
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
			[['regionIds'], 'safe'],
			[['isActive'], 'default', 'value' => 1]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'athleteId' => 'Спортсмен',
			'regionIds' => 'Регионы',
			'dateAdded' => 'Дата начала подписки',
			'dateEnd'   => 'Дата окончания подписки',
			'type'      => 'Тип',
			'isActive'  => 'Активность'
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
	
	public function getCountryIds()
	{
		if ($this->regionIds && !is_array($this->regionIds)) {
			$regionIds = $this->getRegionIds();
		} else {
			$regionIds = $this->regionIds;
		}
		if (!$regionIds) {
			return null;
		}
		
		return Region::find()->select('countryId')->where(['id' => $regionIds])->distinct()->asArray()->column();
	}
}
