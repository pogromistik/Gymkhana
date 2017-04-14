<?php

namespace common\models;

use common\helpers\UserHelper;
use Yii;

/**
 * This is the model class for table "Notices".
 *
 * @property integer $id
 * @property integer $athleteId
 * @property string  $text
 * @property string  $link
 * @property integer $status
 * @property integer $dateAdded
 * @property integer $senderId
 *
 * @property Athlete $athlete
 */
class Notice extends \yii\db\ActiveRecord
{
	const STATUS_NEW = 1;
	const STATUS_DONE = 2;
	
	public static $statusesTitle = [
		self::STATUS_NEW  => 'Новое',
		self::STATUS_DONE => 'Прочитано'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Notices';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'text', 'dateAdded', 'senderId'], 'required'],
			[['athleteId', 'status', 'dateAdded', 'senderId'], 'integer'],
			[['link', 'text'], 'string', 'max' => 255],
			['senderId', 'default', 'value' => UserHelper::CONSOLE_LOG_USER_ID]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'athleteId' => 'Получатель',
			'text'      => 'Текст',
			'link'      => 'Ссылка',
			'status'    => 'Статус',
			'dateAdded' => 'Дата',
			'senderId'  => 'Отправитель'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			$this->senderId = UserHelper::getUserId();
		}
		
		return parent::beforeValidate();
	}
	
	public static function getAll($status = null)
	{
		$result = self::find()->where(['athleteId' => \Yii::$app->user->id]);
		if ($status) {
			$result = $result->andWhere(['status' => $status]);
		}
		
		return $result->all();
	}
	
	public static function add($athleteId, $text, $link = null)
	{
		$notice = new self();
		$notice->athleteId = $athleteId;
		$notice->text = $text;
		if ($link) {
			$notice->link = $link;
		}
		if ($notice->save()) {
			return true;
		}
		
		return false;
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
}
