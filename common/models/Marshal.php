<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "marshals".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $post
 * @property string  $photo
 * @property string  $text1
 * @property string  $text2
 * @property string  $text3
 * @property string  $motorcycle
 * @property string  $motorcyclePhoto
 * @property string  $gif
 * @property string  $link
 * @property integer $sort
 */
class Marshal extends \yii\db\ActiveRecord
{
	public $photoFile;
	public $motorFile;
	public $gifFile;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Marshals';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['text1', 'text2', 'text3'], 'string'],
			[['name', 'post', 'photo', 'motorcycle', 'motorcyclePhoto', 'gif', 'link'], 'string', 'max' => 255],
			[['photoFile', 'motorFile', 'gifFile'], 'file'],
			[['sort'], 'integer']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'              => 'ID',
			'name'            => 'Имя',
			'post'            => 'Должность',
			'photo'           => 'Фотография маршала',
			'photoFile'       => 'Фотография маршала',
			'text1'           => 'Текстовый блок 1',
			'text2'           => 'Текстовый блок 2',
			'text3'           => 'Текстовый блок 3',
			'motorcycle'      => 'Модель мотоцикла',
			'motorcyclePhoto' => 'Фотография мотоцикла',
			'motorFile'       => 'Фотография мотоцикла',
			'gif'             => 'Гифка мотоцикла',
			'gifFile'         => 'Гифка мотоцикла',
			'link'            => 'Ссылка на соц. сети',
			'sort'            => 'Сортировка'
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
