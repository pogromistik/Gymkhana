<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "regional_groups".
 *
 * @property integer $id
 * @property string  $title
 */
class RegionalGroup extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'RegionalGroups';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'title' => \Yii::t('app', 'Название'),
		];
	}
}
