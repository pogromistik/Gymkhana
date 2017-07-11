<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Points".
 *
 * @property integer $id
 * @property integer $point
 */
class Point extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Points';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'point'], 'required'],
			[['id', 'point'], 'integer'],
			[['id'], 'unique'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => \Yii::t('app', 'Место'),
			'point' => \Yii::t('app', 'Балл'),
		];
	}
}
