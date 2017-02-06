<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Regions".
 *
 * @property integer $id
 * @property string  $title
 */
class Region extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Regions';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'title' => 'Название',
		];
	}
}
