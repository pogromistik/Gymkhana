<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "groups_menu".
 *
 * @property integer    $id
 * @property string     $title
 * @property integer    $sort
 * @property MenuItem[] $items
 */
class GroupMenu extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'groups_menu';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['sort'], 'integer'],
			[['title'], 'string', 'max' => 255],
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
			'sort'  => 'Сортировка',
		];
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
	
	public function getItems()
	{
		return $this->hasMany(MenuItem::className(), ['groupsMenuId' => 'id']);
	}
}
