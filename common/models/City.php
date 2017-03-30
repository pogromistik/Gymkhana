<?php

namespace common\models;

use Yii;
use yii\base\Exception;
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
 * @property integer $countryId
 * @property string  $state
 * @property string  $timezone
 * @property string  $utc
 *
 * @property Region  $region
 * @property Country $country
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
			[['title', 'regionId', 'countryId'], 'required'],
			[['showInRussiaPage', 'regionId', 'countryId'], 'integer'],
			[['top', 'left'], 'number'],
			[['title', 'link', 'federalDistrict', 'state', 'timezone', 'utc'], 'string'],
			[['showInRussiaPage'], 'default', 'value' => 0],
			['timezone', 'validateTimeZone']
		];
	}
	
	public function validateTimeZone($attribute, $params)
	{
		if (!$this->hasErrors() && $this->timezone && $this->timezone != '') {
			$exist = false;
			foreach(timezone_abbreviations_list() as $abbr => $timezone){
				foreach($timezone as $val){
					if(isset($val['timezone_id'])){
						if ($val['timezone_id'] == $this->timezone) {
							$exist = true;
							break;
						}
					}
				}
			}
			if (!$exist) {
				$this->addError($attribute, 'Временная зона не существует.');
			}
		}
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
			'regionId'         => 'Регион',
			'countryId'        => 'Страна',
			'timezone'         => 'Временная зона',
			'utc'              => 'Разница с UTC'
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
		if ($this->isNewRecord) {
			if ($this->regionId && !$this->countryId) {
				$this->countryId = $this->region->countryId;
			}
		}
		
		return parent::beforeValidate();
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
	
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'countryId']);
	}
	
	public static function getAll($asArrayHelper = false)
	{
		$result = self::find()->orderBy(['title' => SORT_ASC]);
		if ($asArrayHelper) {
			return ArrayHelper::map($result->all(), 'id', function (Region $item) {
				return $item->title;
			});
		}
		
		return $result->all();
	}
}
