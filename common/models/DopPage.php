<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dop_pages".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $picture
 * @property integer $type
 */
class DopPage extends \yii\db\ActiveRecord
{
	const TYPE_PAGE_NOT_FOUND = 1;
	const TYPE_PAGE_IN_DEVELOPING = 2;

	public $pictureFile;
	public static $typesTitle = [
		self::TYPE_PAGE_NOT_FOUND     => 'Страница не найдена',
		self::TYPE_PAGE_IN_DEVELOPING => 'Страница находится в разработке'
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'DopPages';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'type'], 'required'],
			[['type'], 'integer'],
			[['title', 'picture'], 'string', 'max' => 255],
			[['pictureFile'], 'file']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'title'   => 'Название',
			'picture' => 'Картинка',
			'type'    => 'Тип',
		];
	}
}
