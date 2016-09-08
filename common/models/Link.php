<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "links".
 *
 * @property integer $id
 * @property string  $link
 * @property string  $title
 * @property string  $class
 */
class Link extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'links';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['link', 'title', 'class'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'link'  => 'Ссылка',
			'title' => 'Название',
			'class' => 'Класс',
		];
	}
}
