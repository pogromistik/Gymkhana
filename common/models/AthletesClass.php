<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "athletes_classes".
 *
 * @property integer $id
 * @property integer $title
 * @property integer $percent
 * @property integer $sort
 * @property string  $description
 */
class AthletesClass extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'athletes_classes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'percent'], 'required'],
			[['percent', 'sort'], 'integer'],
			[['description', 'title'], 'string'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => 'Название',
			'percent'     => 'Процент',
			'sort'        => 'Сортировка',
			'description' => 'Описание',
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
