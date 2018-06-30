<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Votes".
 *
 * @property int             $id
 * @property int             $athleteId
 * @property int             $interviewId
 * @property int             $answerId
 * @property int             $dateAdded
 *
 * @property Athlete         $athlete
 * @property InterviewAnswer $answer
 * @property Interview       $interview
 */
class Vote extends \yii\db\ActiveRecord
{
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'Votes';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['athleteId', 'interviewId', 'answerId', 'dateAdded'], 'required'],
			[['athleteId', 'interviewId', 'answerId', 'dateAdded'], 'default', 'value' => null],
			[['athleteId', 'interviewId', 'answerId', 'dateAdded'], 'integer'],
			[['athleteId'], 'exist', 'skipOnError' => true, 'targetClass' => Athlete::class, 'targetAttribute' => ['athleteId' => 'id']],
			[['answerId'], 'exist', 'skipOnError' => true, 'targetClass' => InterviewAnswer::class, 'targetAttribute' => ['answerId' => 'id']],
			[['interviewId'], 'exist', 'skipOnError' => true, 'targetClass' => Interview::class, 'targetAttribute' => ['interviewId' => 'id']],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'athleteId'   => 'Athlete ID',
			'interviewId' => 'Interview ID',
			'answerId'    => 'Answer ID',
			'dateAdded'   => 'Date Added',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAthlete()
	{
		return $this->hasOne(Athlete::class, ['id' => 'athleteId']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAnswer()
	{
		return $this->hasOne(InterviewAnswer::class, ['id' => 'answerId']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInterview()
	{
		return $this->hasOne(Interview::class, ['id' => 'interviewId']);
	}
	
	public function beforeValidate()
	{
		$this->dateAdded = time();

		return parent::beforeValidate();
	}
}
