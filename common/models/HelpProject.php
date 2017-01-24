<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "help_project".
 *
 * @property integer $id
 * @property string  $imgFolder
 * @property string  $text1
 * @property string  $text2
 */
class HelpProject extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'help_project';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['imgFolder', 'text1', 'text2'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'imgFolder' => 'Img Folder',
			'text1'     => 'Первый текст',
			'text2'     => 'Второй текст',
		];
	}
	
	public function beforeValidate()
	{
		if (!$this->imgFolder) {
			$this->imgFolder = 'help-project';
			HelpModel::createFolder($this->imgFolder);
		}
		return parent::beforeValidate();
	}
	
	public function getPhotos()
	{
		$photos = [];
		
		$files = scandir(Yii::getAlias('@files').'/'.$this->imgFolder);
		foreach ($files as $file) {
			if ($file != "." && $file != ".." && !is_dir(Yii::getAlias('@files').'/'.$this->imgFolder.'/'.$file)) {
				$photos[] = $file;
			}
		}
		
		return $photos;
	}
}
