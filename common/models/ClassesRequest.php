<?php

namespace common\models;

use Yii;
use common\components\BaseActiveRecord;

/**
 * This is the model class for table "ClassesRequest".
 *
 * @property integer $id
 * @property integer $dateAdded
 * @property integer $status
 * @property string  $comment
 * @property integer $athleteId
 * @property integer $newClassId
 * @property string  $feedback
 */
class ClassesRequest extends BaseActiveRecord
{
	protected static $enableLogging = true;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ClassesRequest';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['dateAdded', 'comment', 'athleteId', 'newClassId'], 'required'],
			[['dateAdded', 'status', 'athleteId', 'newClassId'], 'integer'],
			[['comment'], 'string'],
			[['feedback'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'dateAdded'  => 'Date Added',
			'status'     => 'Status',
			'comment'    => 'Причина',
			'athleteId'  => 'Спортсмен',
			'newClassId' => 'Новый класс',
			'feedback'   => 'Наш ответ',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		return parent::beforeValidate();
	}
}
