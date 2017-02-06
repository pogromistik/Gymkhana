<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string  $originalTitle
 * @property string  $title
 * @property string  $folder
 * @property integer $dateAdded
 * @property integer $type
 */
class Files extends \yii\db\ActiveRecord
{
	public $file;
	public $picture;
	
	const TYPE_PHOTO = 1;
	const TYPE_DOCUMENTS = 2;
	const TYPE_LOAD_PICTURES = 3;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Files';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['originalTitle', 'title', 'folder', 'dateAdded', 'type'], 'required'],
			[['dateAdded', 'type'], 'integer'],
			[['originalTitle', 'title', 'folder'], 'string', 'max' => 255],
			[['file'], 'file', 'maxFiles' => 10],
			[['picture'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => 10],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'originalTitle' => 'Original Title',
			'title'         => 'Title',
			'folder'        => 'Folder',
			'dateAdded'     => 'Date Added',
			'type'          => 'Type',
			'file'          => 'Файлы',
			'picture'       => 'Изображения'
		];
	}
	
	public function beforeValidate()
	{
		$this->dateAdded = time();
		
		return parent::beforeValidate();
	}
	
	public function saveFile($type, $oneFile = false)
	{
		$folder = null;
		$var = null;
		switch ($type) {
			case self::TYPE_PHOTO:
				$folder = 'pictures';
				$var = 'picture';
				break;
			case self::TYPE_LOAD_PICTURES:
				$folder = 'preloader';
				$var = 'picture';
				break;
			case self::TYPE_DOCUMENTS:
				$folder = 'documents';
				$var = 'file';
				break;
		}
		$dir = \Yii::getAlias('@files') . '/' . $folder;
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		
		$files = UploadedFile::getInstances($this, $var);
		
		if ($files) {
			foreach ($files as $file) {
				$item = new self();
				$item->type = $type;
				$item->originalTitle = $file->name;
				$item->title = uniqid() . '.' . $file->extension;
				$item->folder = $folder . '/' . $item->title;
				if (!$file->saveAs($dir . '/' . $item->title)) {
					return 'error saveAs';
				}
				if (!$item->save()) {
					return $item->errors;
				}
				if ($oneFile) {
					return $item->id;
				}
			}
		}
		if ($oneFile) {
			return false;
		}
		return true;
	}
}
