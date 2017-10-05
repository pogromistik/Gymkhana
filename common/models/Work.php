<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Work".
 *
 * @property integer $id
 * @property integer $status
 * @property string  $text
 * @property integer $dateStart
 * @property integer $time
 */
class Work extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Work';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['status', 'dateStart', 'time'], 'integer'],
			[['text'], 'required'],
			[['text'], 'string'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'status'    => 'Активно',
			'text'      => 'Текст',
			'dateStart' => 'Время старта',
			'time'      => 'Через сколько закончится',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->status === 1) {
			$this->dateStart = time();
		} else {
			$this->dateStart = null;
		}
		
		return parent::beforeValidate();
	}
}
