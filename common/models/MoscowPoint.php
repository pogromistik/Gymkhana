<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "MoscowPoints".
 *
 * @property integer $id
 * @property integer $class
 * @property integer $place
 * @property integer $point
 *
 * @property AthletesClass $classModel
 */
class MoscowPoint extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'MoscowPoints';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['class', 'place', 'point'], 'required'],
			[['class', 'place', 'point'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'class' => \Yii::t('app', 'Группа'),
			'place' => \Yii::t('app', 'Место'),
			'point' => \Yii::t('app', 'Очки'),
		];
	}
	
	public function getClassModel()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'class']);
	}
}
