<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "Interviews".
 *
 * @property int               $id
 * @property int               $dateAdded
 * @property int               $dateUpdated
 * @property int               $dateStart
 * @property int               $dateEnd
 * @property string            $title
 * @property string            $titleEn
 * @property string            $description
 * @property string            $descriptionEn
 * @property int               $onlyPictures
 * @property int               $showResults
 *
 * @property InterviewAnswer[] $interviewAnswers
 * @property Vote[]            $votes
 */
class Interview extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $dateStartHuman;
	public $dateEndHuman;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'Interviews';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['dateAdded', 'dateUpdated', 'dateStartHuman', 'dateEndHuman', 'title'], 'required'],
			[['dateAdded', 'dateUpdated', 'dateStart', 'dateEnd', 'onlyPictures', 'showResults'], 'default', 'value' => null],
			[['dateAdded', 'dateUpdated', 'dateStart', 'dateEnd', 'onlyPictures', 'showResults'], 'integer'],
			[['description', 'descriptionEn'], 'string'],
			[['title', 'titleEn'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id'             => 'ID',
			'dateAdded'      => 'Date Added',
			'dateUpdated'    => 'Date Updated',
			'dateStart'      => 'Начало голосования',
			'dateEnd'        => 'Завершение голосования',
			'dateStartHuman' => 'Начало голосования',
			'dateEndHuman'   => 'Завершение голосования',
			'title'          => 'Название',
			'titleEn'        => 'Название En',
			'description'    => 'Описание',
			'descriptionEn'  => 'Описание En',
			'onlyPictures'   => 'Показывать только картинки',
			'showResults'    => 'Показывать результаты проголосовавшим',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInterviewAnswers()
	{
		return $this->hasMany(InterviewAnswer::class, ['interviewId' => 'id'])->orderBy(['id' => SORT_ASC]);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getVotes()
	{
		return $this->hasMany(Vote::class, ['interviewId' => 'id']);
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		if ($this->dateStartHuman) {
			$this->dateStart = (new \DateTime($this->dateStartHuman, new \DateTimeZone(HelpModel::DEFAULT_TIME_ZONE)))->getTimestamp();
		} else {
			$this->dateStart = null;
		}
		if ($this->dateEndHuman) {
			$this->dateEnd = (new \DateTime($this->dateEndHuman, new \DateTimeZone(HelpModel::DEFAULT_TIME_ZONE)))->getTimestamp();
		} else {
			$this->dateEnd = null;
		}
		if (!$this->onlyPictures) {
			$this->onlyPictures = 0;
		}
		if (!$this->showResults) {
			$this->showResults = 0;
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		
		date_default_timezone_set(HelpModel::DEFAULT_TIME_ZONE);
		if ($this->dateStart) {
			$this->dateStartHuman = date('d.m.Y, H:i', $this->dateStart);
		}
		if ($this->dateEnd) {
			$this->dateEndHuman = date('d.m.Y, H:i', $this->dateEnd);
		}
	}
}
