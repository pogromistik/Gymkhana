<?php

namespace common\models;

use common\helpers\UserHelper;
use Yii;

/**
 * This is the model class for table "Messages".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $text
 * @property integer $userId
 * @property integer $dateAdded
 */
class Message extends \yii\db\ActiveRecord
{
	public $athleteIds;
	public $stageId;
	
	const TYPE_TO_ATHLETES = 1;
	const TYPE_TO_PARTICIPANTS = 2;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Messages';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'text', 'userId', 'dateAdded'], 'required'],
			[['text'], 'string'],
			[['userId', 'dateAdded', 'stageId'], 'integer'],
			[['title'], 'string', 'max' => 255],
			[['athleteIds'], 'safe']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'title'      => 'Заголовок',
			'text'       => 'Текст сообщения',
			'userId'     => 'Отправитель',
			'dateAdded'  => 'Дата',
			'athleteIds' => 'Спортсмены, которым будет отправлено письмо',
			'stageId'    => 'Этап, всем участникам которого будет отправлено письмо'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->userId = UserHelper::getUserId();
			$this->dateAdded = time();
		}
		$this->text = trim($this->text);
		$this->title = trim($this->title);
		
		return parent::beforeValidate();
	}
}
