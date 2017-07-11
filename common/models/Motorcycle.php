<?php

namespace common\models;

use common\components\BaseActiveRecord;
use common\helpers\UserHelper;
use Yii;

/**
 * This is the model class for table "motorcycles".
 *
 * @property integer $id
 * @property integer $athleteId
 * @property string  $mark
 * @property string  $model
 * @property integer $internalClassId
 * @property integer $dateAdded
 * @property integer $dateUpdated
 * @property integer $status
 * @property integer $creatorUserId
 *
 * @property Athlete $athlete
 */
class Motorcycle extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;
	public static $statusesTitle = [
		self::STATUS_ACTIVE   => 'Активен',
		self::STATUS_INACTIVE => 'Неактивен'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Motorcycles';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'mark', 'model', 'dateAdded', 'dateUpdated'], 'required'],
			[['athleteId', 'internalClassId', 'dateAdded', 'dateUpdated', 'status'], 'integer'],
			[['mark', 'model'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'              => 'ID',
			'athleteId'       => \Yii::t('app', 'Спортсмен'),
			'mark'            => \Yii::t('app', 'Марка'),
			'model'           => \Yii::t('app', 'Модель'),
			'internalClassId' => \Yii::t('app', 'Класс награждения'),
			'dateAdded'       => \Yii::t('app', 'Дата добавления'),
			'dateUpdated'     => \Yii::t('app', 'Дата редактирования'),
			'status'          => \Yii::t('app', 'Статус'),
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			$this->creatorUserId = UserHelper::getUserId();
		}
		$this->model = trim($this->model);
		$this->mark = trim($this->mark);
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getFullTitle()
	{
		return $this->mark . ' ' . $this->model;
	}
}
