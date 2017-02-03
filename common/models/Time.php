<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "times".
 *
 * @property integer     $id
 * @property integer     $participantId
 * @property integer     $stageId
 * @property integer     $time
 * @property integer     $fine
 * @property integer     $resultTime
 * @property integer     $attemptNumber
 *
 * @property Participant $participant
 * @property Stage       $stage
 */
class Time extends \yii\db\ActiveRecord
{
	public $timeForHuman;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'times';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['participantId', 'stageId', 'time', 'resultTime', 'attemptNumber', 'timeForHuman'], 'required'],
			[['participantId', 'stageId', 'time', 'fine', 'resultTime', 'attemptNumber'], 'integer'],
			['timeForHuman', 'string'],
			['fine', 'default', 'value' => 0]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'participantId' => 'Участник',
			'stageId'       => 'Этап',
			'time'          => 'Время заезда',
			'timeForHuman'  => 'Время заезда',
			'fine'          => 'Штраф',
			'resultTime'    => 'Итоговое время заезда',
			'attemptNumber' => 'Номер попытки',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->attemptNumber = self::find()->where(['participantId' => $this->participantId,
			                                            'stageId'       => $this->stageId])->max('attemptNumber') + 1;
		}
		if ($this->timeForHuman) {
			list($min, $secs) = explode(':', $this->timeForHuman);
			$this->time = ($min * 60000) + $secs*1000;
		}
		$this->resultTime = $this->time + $this->fine*1000;
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->time) {
			$min = str_pad(floor($this->time / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->time - $min*60000)/1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->time-$min*60000-$sec*1000)/10, 2, '0', STR_PAD_LEFT);
			$this->timeForHuman = $min.':'.$sec.'.'.$mls;
		}
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		$participant = $this->participant;
		$participant->bestTime = self::find()->where(['participantId' => $this->participantId,
		                                              'stageId'       => $this->stageId])->min('resultTime');
		$participant->save();
	}
	
	public function getParticipant()
	{
		return $this->hasOne(Participant::className(), ['id' => 'participantId']);
	}
	
	public function getStage()
	{
		return $this->hasOne(Stage::className(), ['id' => 'stageId']);
	}
}
