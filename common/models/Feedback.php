<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "Feedback".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $phone
 * @property string  $email
 * @property string  $text
 * @property integer $dateAdded
 * @property integer $dateUpdated
 * @property integer $athleteId
 * @property integer $isNew
 *
 * @property Athlete $athlete
 */
class Feedback extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $phoneOrMail;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Feedback';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'dateAdded', 'dateUpdated', 'text'], 'required'],
			[['text'], 'string'],
			[['dateAdded', 'dateUpdated', 'athleteId', 'isNew'], 'integer'],
			[['phone', 'email', 'phoneOrMail'], 'string', 'max' => 255],
			[['isNew'], 'default', 'value' => 1]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'username'    => 'Имя',
			'phone'       => 'Телефон',
			'email'       => 'Email',
			'text'        => 'Текст',
			'dateAdded'   => 'Дата добавления',
			'dateUpdated' => 'Дата редактирования',
			'athleteId'   => 'Спортсмен'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if ($this->phoneOrMail) {
				$this->phoneOrMail = strip_tags($this->phoneOrMail);
				if (mb_stripos($this->phoneOrMail, '@')) {
					$this->email = $this->phoneOrMail;
				} else {
					$this->phone = $this->phoneOrMail;
				}
			}
			if ($this->phone) {
				$this->phone = strip_tags($this->phone);
				$this->phone = preg_replace('~\D+~', '', $this->phone);
			}
			if ($this->text) {
				$this->text = strip_tags($this->text);
			}
		}
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
}
