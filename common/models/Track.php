<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tracks".
 *
 * @property integer $id
 * @property string  $photoPath
 * @property integer $documentId
 * @property string  $description
 * @property string  $title
 * @property Files   $document
 */
class Track extends \yii\db\ActiveRecord
{
	public $photoFile;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'tracks';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['description', 'title'], 'required'],
			[['documentId'], 'integer'],
			[['description'], 'string'],
			[['photoPath', 'title'], 'string', 'max' => 255],
			[['photoFile'], 'file'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'photoPath'   => 'Фотография',
			'documentId'  => 'Документ',
			'description' => 'Описание',
			'title'       => 'Заголовок',
			'photoFile'   => 'Фотография'
		];
	}
	
	public function getDocument()
	{
		return $this->hasOne(Files::className(), ['id' => 'documentId']);
	}
}
