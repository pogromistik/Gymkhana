<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "overall_files".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $date
 * @property string  $modelClass
 * @property string  $modelId
 * @property string  $title
 * @property string  $fileName
 * @property string  $filePath
 */
class OverallFile extends \yii\db\ActiveRecord
{
	public $files;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'overall_files';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['userId', 'date'], 'integer'],
			[['modelClass', 'modelId', 'title', 'fileName', 'filePath'], 'string', 'max' => 255],
			[['files'], 'file', 'maxFiles' => 10]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'userId'     => 'User ID',
			'date'       => 'Date',
			'modelClass' => 'Model Class',
			'modelId'    => 'Model ID',
			'title'      => 'Title',
			'fileName'   => 'File Name',
			'filePath'   => 'File Path',
			'files'      => 'Файлы'
		];
	}
	
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'userId']);
	}
	
	
	public function saveFile($modelId, $modelClass)
	{
		$folder = 'overall-files';
		$dir = \Yii::getAlias('@files') . '/' . $folder;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		
		$files = UploadedFile::getInstances($this, 'files');
		
		if ($files) {
			foreach ($files as $file) {
				$item = new self();
				$item->date = time();
				$item->userId = \Yii::$app->user->identity->id;
				$newName = uniqid() . '.' . $file->extension;
				$item->filePath = $folder . '/' . $newName;
				$item->title = $file->baseName;
				$item->fileName = $file->name;
				$item->modelId = (string)$modelId;
				$item->modelClass = $modelClass;
				if (!$file->saveAs($dir . '/' . $newName)) {
					return 'error saveAs';
				}
				if (!$item->save()) {
					return $item->errors;
				}
			}
		}
		
		return true;
	}
}
