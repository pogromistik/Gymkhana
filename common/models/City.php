<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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
 * @property integer $regionId
 *
 * @property Region  $region
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
			[['title', 'regionId'], 'required'],
			[['showInRussiaPage', 'regionId'], 'integer'],
			[['top', 'left'], 'number'],
			[['title', 'link', 'federalDistrict'], 'string'],
			[['showInRussiaPage'], 'default', 'value' => 0],
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
			'regionId'         => 'Регион'
		];
	}
	
	public function init()
	{
		$this->showInRussiaPage = 1;
		parent::init();
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		if (isset($changedAttributes['regionId'])) {
			Athlete::updateAll(['regionId' => $this->regionId], ['cityId' => $this->id]);
			Stage::updateAll(['regionId' => $this->regionId], ['cityId' => $this->id]);
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function beforeValidate()
	{
		return parent::beforeValidate();
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
	
	public static function getAll($asArrayHelper = false)
	{
		$result = self::find()->orderBy(['title' => SORT_ASC]);
		if ($asArrayHelper) {
			return ArrayHelper::map($result->all(), 'id', 'title');
		}
		return $result->all();
	}
}
