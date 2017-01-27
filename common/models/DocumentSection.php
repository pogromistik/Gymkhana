<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "document_sections".
 *
 * @property integer $id
 * @property string  $title
 */
class DocumentSection extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'document_sections';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['title'], 'string', 'max' => 255],
			[['status'], 'default', 'value' => 1]
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
