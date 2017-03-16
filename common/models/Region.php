<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Regions".
 *
 * @property integer $id
 * @property string  $title
 * @property integer $countryId
 *
 * @property Country  $country
 */
class Region extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Regions';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['countryId', 'title'], 'required'],
			[['title'], 'string', 'max' => 255],
			['countryId', 'integer']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'title'     => 'Название',
			'countryId' => 'Страна'
		];
	}
	
	public static function getAll($asArrayHelper = false, $countryIds = null)
	{
		$result = self::find();
		if ($countryIds !== null) {
			$result = $result->andWhere(['countryId' => $countryIds]);
		}
		$result = $result->orderBy(['title' => SORT_ASC]);
		if ($asArrayHelper) {
			return ArrayHelper::map($result->all(), 'id', function (Region $item) {
				return $item->title;
			});
		}
		
		return $result->all();
	}
	
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'countryId']);
	}
}
