<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "about_block".
 *
 * @property integer       $id
 * @property string        $text
 * @property string        $sliderText
 * @property integer       $sort
 *
 * @property AboutSlider[] $aboutSliders
 */
class AboutBlock extends \yii\db\ActiveRecord
{
	public $slider;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'AboutBlock';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['text'], 'string'],
			[['sort'], 'integer'],
			[['sliderText'], 'string', 'max' => 255],
			[['slider'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => 10]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'text'       => 'Текст',
			'sliderText' => 'Текст для слайдера',
			'sort'       => 'Сортировка',
			'slider'     => 'Слайдер'
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAboutSliders()
	{
		return $this->hasMany(AboutSlider::className(), ['blockId' => 'id'])->orderBy(['sort' => SORT_ASC]);
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
