<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "russia".
 *
 * @property integer $id
 * @property integer $title
 * @property integer $link
 * @property double  $top
 * @property double  $left
 * @property integer $showInRussiaPage
 * @property string  $federalDistrict
 * @property string  $region
 */
class City extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Cities';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['showInRussiaPage'], 'integer'],
			[['top', 'left'], 'number'],
			[['title', 'link', 'federalDistrict', 'region'], 'string'],
			[['showInRussiaPage'], 'default', 'value' => 1],
			['title', 'unique'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'title'            => 'Город',
			'link'             => 'Ссылка',
			'top'              => 'Top',
			'left'             => 'Left',
			'showInRussiaPage' => 'Показывать на странице "Россия"',
			'federalDistrict'  => 'Федеральный округ',
			'region'           => 'Регион'
		];
	}
	
	public function init()
	{
		$this->showInRussiaPage = 1;
		parent::init();
	}
	
	public function beforeValidate()
	{
		return parent::beforeValidate();
	}
}
