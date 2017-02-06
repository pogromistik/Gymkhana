<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "links".
 *
 * @property integer $id
 * @property string  $link
 * @property string  $title
 * @property string  $class
 * @property $sort
 */
class Link extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Links';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['link', 'title'], 'required'],
			[['link', 'title', 'class'], 'string', 'max' => 255],
			['sort', 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'link'  => 'Ссылка',
			'title' => 'Название',
			'class' => 'Класс',
			'sort' => 'Сортировка'
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
