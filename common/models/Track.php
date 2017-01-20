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
 * @property integer $sort
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
			[['documentId', 'sort'], 'integer'],
			[['description'], 'string'],
			[['photoPath', 'title'], 'string', 'max' => 255],
			[['description'], 'string', 'max' => 605],
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
			'photoFile'   => 'Фотография',
			'sort'        => 'Сортировка'
		];
	}
	
	public function getDocument()
	{
		return $this->hasOne(Files::className(), ['id' => 'documentId']);
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->max('sort') + 1;
			}
		}
		
		return parent::beforeValidate();
	}
}
