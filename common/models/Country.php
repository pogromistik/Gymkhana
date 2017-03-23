<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Countries".
 *
 * @property integer  $id
 * @property string   $title
 * @property string   $title_en
 * @property string   $title_original
 *
 * @property City[]   $cities
 * @property Region[] $regions
 */
class Country extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Countries';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['title', 'required'],
			[['title', 'title_en', 'title_original'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'             => 'ID',
			'title'          => 'Название на русском',
			'title_en'       => 'Название на английском',
			'title_original' => 'Оригинальное название'
		];
	}
	
	public static function getAll($asArrayHelper = false)
	{
		$result = self::find()->orderBy(['title' => SORT_ASC]);
		if ($asArrayHelper) {
			return ArrayHelper::map($result->all(), 'id', function (Country $item) {
				if ($item->title_original) {
					return html_entity_decode($item->title . ' (' . $item->title_original . ')');
				} elseif ($item->title_en) {
					return $item->title . ' (' . $item->title_en . ')';
				}
				
				return $item->title;
			});
		}
		
		return $result->all();
	}
	
	public function getRegions()
	{
		return $this->hasMany(Region::className(), ['countryId' => 'id'])->orderBy(['title' => SORT_ASC]);
	}
	
	public function getCities()
	{
		return $this->hasMany(City::className(), ['countryId' => 'id'])->orderBy(['title' => SORT_ASC]);
	}
}
