<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "athletes_classes".
 *
 * @property integer $id
 * @property integer $title
 * @property double  $percent
 * @property integer $sort
 * @property string  $description
 * @property double  $coefficient
 */
class AthletesClass extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'AthletesClasses';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'coefficient', 'percent'], 'required'],
			[['sort'], 'integer'],
			[['percent', 'coefficient'], 'number'],
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
			'coefficient' => 'Коеффициент'
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
