<?php

namespace common\models;

use Yii;
use common\components\BaseActiveRecord;

/**
 * This is the model class for table "ClassesRequest".
 *
 * @property integer       $id
 * @property integer       $dateAdded
 * @property integer       $status
 * @property string        $comment
 * @property integer       $athleteId
 * @property integer       $newClassId
 * @property string        $feedback
 *
 * @property AthletesClass $class
 * @property Athlete       $athlete
 */
class ClassesRequest extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public function getAttributeDisplayValue($attribute, $value)
	{
		switch ($attribute) {
			case 'status':
				return isset(self::$statusesTitle[$value]) ? self::$statusesTitle[$value] : $value;
				break;
			default:
				return parent::getAttributeDisplayValue($attribute, $value);
				break;
		}
	}
	
	const STATUS_NEW = 0;
	const STATUS_APPROVE = 1;
	const STATUS_CANCEL = 2;
	
	public static $statusesTitle = [
		self::STATUS_NEW     => 'Новый запрос',
		self::STATUS_APPROVE => 'Подтверждён',
		self::STATUS_CANCEL  => 'Отклонён'
	];
	
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
			'status'     => \Yii::t('app', 'Статус'),
			'comment'    => \Yii::t('app', 'Причина'),
			'athleteId'  => \Yii::t('app', 'Спортсмен'),
			'newClassId' => \Yii::t('app', 'Новый класс'),
			'feedback'   => \Yii::t('app', 'Наш ответ'),
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->comment = strip_tags($this->comment);
		
		return parent::beforeValidate();
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'newClassId']);
	}
}
