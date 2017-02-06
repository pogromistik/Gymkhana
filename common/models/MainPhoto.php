<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "main_photo".
 *
 * @property integer $id
 * @property string  $fileName
 * @property integer $type
 * @property integer $sort
 * @property integer $dateAdded
 */
class MainPhoto extends \yii\db\ActiveRecord
{
	const PICTURES_SLIDER = 1;
	const PICTURES_RIGHT_MENU = 2;
	const PICTURES_BOTTOM_MENU = 3;

	public static $filePath = [
		self::PICTURES_SLIDER      => 'main/slider',
		self::PICTURES_RIGHT_MENU  => 'left_menu',
		self::PICTURES_BOTTOM_MENU => 'bottom_menu'
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'MainPhoto';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type', 'dateAdded', 'sort'], 'integer'],
			[['fileName'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'fileName'  => 'File Name',
			'type'      => 'Type',
			'dateAdded' => 'Date Added',
		];
	}

	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if (!$this->sort) {
				$this->sort = self::find()->max('sort') + 1;
			}
		}

		return parent::beforeValidate(); // TODO: Change the autogenerated stub
	}
}
