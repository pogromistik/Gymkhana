<?php

namespace common\models;

use common\components\BaseActiveRecord;
use common\components\Resize;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "InterviewAnswers".
 *
 * @property int       $id
 * @property int       $interviewId
 * @property int       $imgPath
 * @property string    $text
 * @property string    $textEn
 * @property int       $votesCount
 *
 * @property Interview $interview
 * @property Vote[]    $votes
 */
class InterviewAnswer extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	public $photoFile;
	
	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'InterviewAnswers';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['interviewId', 'text'], 'required'],
			[['interviewId', 'votesCount'], 'integer'],
			[['text', 'textEn', 'imgPath'], 'string', 'max' => 255],
			['photoFile', 'file', 'extensions' => 'png, jpg', 'maxFiles' => 1, 'maxSize' => 2097152,
			                      'tooBig'     => 'Размер файла не должен превышать 2MB'],
			['photoFile', 'image', 'maxWidth' => 3000, 'maxHeight' => 3000],
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
			'interviewId' => 'Interview ID',
			'imgPath'     => 'Путь к изображению',
			'photoFile'   => 'Изображение',
			'text'        => 'Текст',
			'textEn'      => 'Текст En',
			'votesCount'  => 'Коилчество голосов',
		];
	}
	
	public function beforeValidate()
	{
		if (!$this->votesCount) {
			$this->votesCount = 0;
		}
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'photoFile');
		if ($file && $file->size <= 2097152) {
			if ($this->imgPath) {
				HelpModel::deleteFile($this->imgPath);
			}
			$dir = \Yii::getAlias('@files') . '/' . 'interviews';
			if (!file_exists($dir)) {
				mkdir($dir);
			}
			$title = uniqid() . '.' . $file->extension;
			$folder = $dir . '/' . $title;
			if ($file->saveAs($folder)) {
				$this->imgPath = 'interviews/' . $title;
				Resize::resizeImage($folder);
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInterview()
	{
		return $this->hasOne(Interview::class, ['id' => 'interviewId']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getVotes()
	{
		return $this->hasMany(Vote::class, ['answerId' => 'id']);
	}
}
