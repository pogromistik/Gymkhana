<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "internal_classes".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property integer $championshipId
 * @property integer $status
 * @property integer $cheId
 */
class InternalClass extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	
	public static $statusesTitle = [
		self::STATUS_ACTIVE   => 'Активен',
		self::STATUS_INACTIVE => 'Удалён'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'InternalClasses';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'championshipId'], 'required'],
			[['description'], 'string'],
			[['championshipId', 'status', 'cheId'], 'integer'],
			['status', 'default', 'value' => 1],
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'             => 'ID',
			'title'          => \Yii::t('app', 'Название'),
			'description'    => \Yii::t('app', 'Описание'),
			'championshipId' => \Yii::t('app', 'Чемпионат'),
			'status'         => \Yii::t('app', 'Статус'),
			'cheId'          => \Yii::t('app', 'Идентификатор класса из Челябинской схемы')
		];
	}
	
	public static function getActiveClasses($championshipId = null)
	{
		$result = self::find()->where(['status' => self::STATUS_ACTIVE]);
		if ($championshipId) {
			$result = $result->andWhere(['championshipId' => $championshipId]);
		}
		
		return $result->all();
	}
}
