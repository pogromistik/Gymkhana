<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "video_types".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $picture
 * @property integer $status
 */
class VideoType extends \yii\db\ActiveRecord
{
	public $pictureFile;

	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;

	public static $statusesTitle = [
		self::STATUS_ACTIVE   => 'активен',
		self::STATUS_INACTIVE => 'не активен'
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'VideoTypes';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'status'], 'required'],
			[['title', 'picture'], 'string', 'max' => 255],
			[['status'], 'default', 'value' => 1],
			['pictureFile', 'file']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => 'Раздел',
			'status'      => 'Статус',
			'picture'     => 'Пиктограмма',
			'pictureFile' => 'Пиктограмма'
		];
	}
	
	public static function getActive()
	{
		return self::findAll(['status' => self::STATUS_ACTIVE]);
	}
}
