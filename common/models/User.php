<?php

namespace common\models;

use dektrium\user\models\User as BaseUser;

/**
 * Class User
 * @package common\models
 * @inheritdoc
 * @property  integer $regionId
 * @property integer $showHint
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
		
		$scenarios['create'][]   = 'showHint';
		$scenarios['update'][]   = 'showHint';
		$scenarios['register'][] = 'showHint';
		return $scenarios;
	}
	
	public function rules()
	{
		$rules = parent::rules();
		// add some rules
		$rules['regionIdRequired'] = ['regionId', 'required'];
		$rules['regionIdType'] = ['regionId', 'integer'];
		
		$rules['showHintType'] = ['showHint', 'integer'];
		$rules['showHintDefault'] = ['showHint', 'default', 'value' => 1];
		
		return $rules;
	}
	
	public function attributeLabels()
	{
		$labels = parent::attributeLabels();
		$labels['regionId'] = 'Регион';
		$labels['showHint'] = 'Показывать подсказки';
		return $labels;
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
}