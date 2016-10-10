<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "albums".
 *
 * @property integer $id
 * @property string  $title
 * @property integer $yearId
 * @property string  $folder
 * @property string  $cover
 * @property integer $dateAdded
 * @property string  $description
 *
 * @property Year    $year
 */
class Album extends \yii\db\ActiveRecord
{
	public $coverFile;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'albums';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'yearId', 'dateAdded'], 'required'],
			[['yearId', 'dateAdded'], 'integer'],
			[['title', 'folder', 'cover', 'description'], 'string', 'max' => 255],
			['coverFile', 'file']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => 'Название',
			'yearId'      => 'Год',
			'folder'      => 'Папка',
			'cover'       => 'Обложка',
			'dateAdded'   => 'Дата создания',
			'description' => 'Описание',
			'coverFile'   => 'Обложка'
		];
	}

	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}

		return parent::beforeValidate();
	}

	public function getYear()
	{
		return $this->hasOne(Year::className(), ['id' => 'yearId']);
	}
}
