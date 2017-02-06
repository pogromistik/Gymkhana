<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "layouts".
 *
 * @property string $id
 * @property string $title
 *
 * @property Page[] $pages
 */
class Layout extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Layouts';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'title'], 'required'],
			[['id', 'title'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'title' => 'Название',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPages()
	{
		return $this->hasMany(Page::className(), ['layout' => 'id']);
	}
}
