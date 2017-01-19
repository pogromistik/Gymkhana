<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "main_menu".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $sort
 * @property integer $pageId
 * @property string  $link
 * @property string  $type
 * @property Page    $page
 */
class MainMenu extends \yii\db\ActiveRecord
{
	
	const TYPE_GREEN_ITEMS = 1;
	const TYPE_MAIN_ITEMS = 2;
	const TYPE_BIG_GRAY_SQUARE = 3;
	const TYPE_ANIMATE_SQUARE = 4;
	
	public static $typesTitle = [
		self::TYPE_GREEN_ITEMS     => 'Пункты на зелёной полосе',
		self::TYPE_MAIN_ITEMS      => 'Основное меню, идущее списком',
		self::TYPE_BIG_GRAY_SQUARE => 'Три серых квадрата',
		self::TYPE_ANIMATE_SQUARE  => 'Квадраты с фотками'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'main_menu';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type'], 'required'],
			[['pageId', 'sort'], 'integer'],
			[['title', 'link', 'type'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'     => 'ID',
			'title'  => 'Название',
			'sort'   => 'Сортировка',
			'pageId' => 'Страница',
			'link'   => 'Ссылка',
			'type'   => 'Тип',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->where(['type' => $this->type])->max('sort') + 1;
			}
		}
		
		return parent::beforeValidate();
	}
	
	public function getPage()
	{
		return $this->hasOne(Page::className(), ['id' => 'pageId']);
	}
}
