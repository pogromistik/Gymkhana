<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Errors".
 *
 * @property integer $id
 * @property integer $type
 * @property string  $text
 * @property integer $status
 * @property integer $dateAdded
 * @property integer $dateUpdated
 */
class Error extends \yii\db\ActiveRecord
{
	const TYPE_OTHER = 1;
	const TYPE_SIZE = 2;
	const TYPE_CRITICAL_ERROR = 3;
	public static $typesTitle = [
		self::TYPE_OTHER          => 'Другое',
		self::TYPE_CRITICAL_ERROR => 'Критическая ошибка',
		self::TYPE_SIZE           => 'Недостаток места на хостинге'
	];
	
	const STATUS_NEW = 0;
	const STATUS_FIXED = 1;
	public static $statusesTitle = [
		self::STATUS_NEW   => 'Новая',
		self::STATUS_FIXED => 'Исправленная'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Errors';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type', 'status', 'dateAdded', 'dateUpdated'], 'integer'],
			[['text'], 'string'],
			[['dateAdded', 'dateUpdated'], 'required'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'type'        => 'Тип',
			'text'        => 'Текст',
			'status'      => 'Статус',
			'dateAdded'   => 'Дата создания',
			'dateUpdated' => 'Дата редактирования',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
}
