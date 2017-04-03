<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Errors".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $text
 */
class Errors extends \yii\db\ActiveRecord
{
	const TYPE_OTHER = 1;
	const TYPE_DB = 2;
	const TYPE_CRITICAL_ERROR = 3;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Errors';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type'], 'integer'],
			[['text'], 'string'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'   => 'ID',
			'type' => 'Type',
			'text' => 'Text',
		];
	}
}
