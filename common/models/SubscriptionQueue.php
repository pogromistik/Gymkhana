<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SubscriptionsQueue".
 *
 * @property integer $id
 * @property integer $countEmails
 * @property integer $type
 * @property integer $messageType
 * @property integer $modelId
 * @property integer $dateAdded
 * @property integer $dateSend
 * @property integer $isActual
 */
class SubscriptionQueue extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'SubscriptionsQueue';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['countEmails', 'type', 'messageType', 'modelId', 'dateAdded', 'dateSend', 'isActual'], 'integer'],
			[['type', 'messageType', 'modelId', 'dateAdded'], 'required'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'countEmails' => 'Count Emails',
			'type'        => 'Type',
			'messageType' => 'Message Type',
			'modelId'     => 'Model ID',
			'dateAdded'   => 'Date Added',
			'dateSend'    => 'Date Send',
			'isActual'    => 'Is Actual',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		
		return parent::beforeValidate();
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		if ($insert) {
			self::updateAll(['isActual' => 0], [
				'and',
				['isActual'    => 1],
				['type'        => $this->type],
				['messageType' => $this->messageType],
				['modelId'     => $this->modelId],
				['not', ['id' => $this->id]]
			]);
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	private static function isExist($type, $messageType, $modelId)
	{
		if (self::find()->where(['type' => $type])->andWhere(['messageType' => $messageType])->andWhere(['modelId' => $modelId])->one()) {
			return true;
		}
		return false;
	}
	
	public static function addToQueue($type, $messageType, $modelId)
	{
		//Если такая рассылка уже была - не создаём её повторно
		if (self::isExist($type, $messageType, $modelId)) {
			return true;
		}
		$item = new self();
		$item->type = $type;
		$item->messageType = $messageType;
		$item->modelId = $modelId;
		$item->save();
		
		return true;
	}
}
