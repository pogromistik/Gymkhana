<?php

namespace common\models;

use common\helpers\HipChatHelper;
use Yii;

/**
 * This is the model class for table "source_message".
 *
 * @property integer            $id
 * @property string             $category
 * @property string             $message
 * @property integer            $status
 *
 * @property TranslateMessage[] $messages
 */
class TranslateMessageSource extends \yii\db\ActiveRecord
{
	const STATUS_WAIT = 0;
	const STATUS_ACTIVE = 1;
	
	public static $statusesTitle = [
		self::STATUS_WAIT   => 'Ожидает проверки',
		self::STATUS_ACTIVE => 'Активно'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TranslateMessageSource';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['message'], 'required'],
			[['message', 'comment'], 'string'],
			[['status'], 'integer'],
			[['category'], 'string', 'max' => 255],
			[['message'], 'messageCheck'],
			['category', 'default', 'value' => 'app'],
			[['status'], 'default', 'value' => self::STATUS_ACTIVE]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'       => 'ID',
			'category' => 'Категория',
			'message'  => 'Сообщение',
			'status'   => 'Статус',
			'comment'  => 'Комментарий для переводчика'
		];
	}
	
	public function messageCheck($attributeNames = null, $clearErrors = true)
	{
		$category = $this->category;
		if (!$category) {
			$category = 'app';
		}
		if (self::find()->where(['message' => $this->message, 'category' => $category])->andWhere(['not', ['id' => $this->id]])->one()) {
			$this->addError($attributeNames, 'Данное сообщение уже есть в системе');
		}
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMessages()
	{
		return $this->hasMany(TranslateMessage::className(), ['id' => 'id']);
	}
	
	/**
	 * @param $language
	 *
	 * @return TranslateMessage
	 */
	public function getMessageForm($language)
	{
		$form = TranslateMessage::findOne(['id' => $this->id, 'language' => $language]);
		if (!$form) {
			$form = new TranslateMessage();
			$form->id = $this->id;
			$form->language = $language;
		}
		
		return $form;
	}
	
	public function beforeValidate()
	{
		$this->message = trim($this->message);
		
		return parent::beforeValidate();
	}
	
	/*
	public function afterSave($insert, $changedAttributes)
	{
		if (isset($changedAttributes['message'])) {
			HipChatHelper::send(HipChatHelper::ROOM_TRANSLATE_MESSAGE,
				'Изменение сообщения с ' . $changedAttributes['message'] . ' на ' . $this->message,
				'yellow');
			if (defined('APP_ENV') && APP_ENV == APP_ENV_PROD && $this->messages) {
				\Yii::$app->mailer->compose('@common/mail/text',
					[
						'text' => 'Изменение сообщения с ' . $changedAttributes['message'] . ' на ' . $this->message
					])->setTo([
					'caocaoru2@gmail.com'
				])
					->setSubject('Изменение исходного текста для переведенного сообщения')->send();
			}
		}
		parent::afterSave($insert, $changedAttributes);
	} */
}
