<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "athletes_classes".
 *
 * @property integer $id
 * @property integer $title
 * @property double  $percent
 * @property integer $sort
 * @property string  $description
 * @property double  $coefficient
 * @property integer $status
 */
class AthletesClass extends \yii\db\ActiveRecord
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'AthletesClasses';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'coefficient', 'percent'], 'required'],
			[['sort', 'status'], 'integer'],
			[['percent', 'coefficient'], 'number'],
			[['description', 'title'], 'string'],
			['status', 'default', 'value' => 1]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => 'Название',
			'percent'     => 'Процент',
			'sort'        => 'Сортировка',
			'description' => 'Описание',
			'coefficient' => 'Коэффициент'
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
	
	/**
	 * @return null|AthletesClass
	 */
	public static function getStartClass()
	{
		return self::find()->where(['status' => AthletesClass::STATUS_ACTIVE])
			->orderBy(['percent' => SORT_DESC])->one();
	}
	
	public static function getList()
	{
		$result = self::find()->orderBy(['percent' => SORT_ASC, 'title' => SORT_ASC])->all();
		if (!$result) {
			return null;
		}
		
		return ArrayHelper::map($result,'id', 'title');
	}
}
