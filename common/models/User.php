<?php

namespace common\models;

use dektrium\user\models\User as BaseUser;

/**
 * Class User
 * @package common\models
 * @inheritdoc
 * @property  bool $useSmartPrinting
 */
class User extends BaseUser
{
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		// add field to scenarios
		$scenarios['create'][]   = 'regionId';
		$scenarios['update'][]   = 'regionId';
		$scenarios['register'][] = 'regionId';
		return $scenarios;
	}
	
	public function rules()
	{
		$rules = parent::rules();
		// add some rules
		$rules['regionIdRequired'] = ['regionId', 'required'];
		$rules['regionIdType'] = ['regionId', 'integer'];
		
		return $rules;
	}
	
	public function attributeLabels()
	{
		$labels = parent::attributeLabels();
		$labels['regionId'] = 'Регион';
		return $labels;
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
}