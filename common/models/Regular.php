<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regular".
 *
 * @property integer $id
 * @property string  $text
 * @property integer $sort
 */
class Regular extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'regular';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['text'], 'required'],
			[['text'], 'string'],
			[['sort'], 'integer'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'   => 'ID',
			'text' => 'Текст',
			'sort' => 'Сортировка',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->max('sort') + 1;
			}
		}
		
		return parent::beforeValidate();
	}
}
