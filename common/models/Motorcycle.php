<?php

namespace common\models;

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
 * @property Athlete $athlete
 */
class Motorcycle extends \yii\db\ActiveRecord
{
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
		return 'motorcycles';
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
			'athleteId'       => 'Спортсмен',
			'mark'            => 'Марка',
			'model'           => 'Модель',
			'internalClassId' => 'Класс награждения',
			'dateAdded'       => 'Добавлен',
			'dateUpdated'     => 'Обновлен',
			'status'          => 'Статус',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
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
