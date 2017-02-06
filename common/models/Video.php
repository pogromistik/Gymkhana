<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "video".
 *
 * @property integer   $id
 * @property string    $title
 * @property integer   $typeId
 * @property string    $description
 * @property string    $link
 * @property integer   $dateAdded
 * @property integer   $dateUpdated
 *
 * @property VideoType $type
 */
class Video extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Video';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'typeId', 'link', 'dateAdded', 'dateUpdated'], 'required'],
			[['typeId', 'dateAdded', 'dateUpdated'], 'integer'],
			[['title', 'description', 'link'], 'string', 'max' => 255],
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
			'typeId'      => 'Раздел',
			'description' => 'Описание',
			'link'        => 'Ссылка',
			'dateAdded'   => 'Добавлено',
			'dateUpdated' => 'Обновлено',
		];
	}

	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();

		return parent::beforeValidate();
	}

	public function getType()
	{
		return $this->hasOne(VideoType::className(), ['id' => 'typeId']);
	}
}
