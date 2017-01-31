<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regional_groups".
 *
 * @property integer $id
 * @property string  $title
 */
class RegionalGroup extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'regional_groups';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
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
