<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CheScheme".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $description
 * @property double  $percent
 */
class CheScheme extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'CheScheme';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['percent'], 'number'],
			[['title', 'description'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => \Yii::t('app', 'Название'),
			'description' => \Yii::t('app', 'Описание'),
			'percent'     => \Yii::t('app', 'Процент'),
		];
	}
}
