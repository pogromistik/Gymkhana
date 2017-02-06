<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_items".
 *
 * @property integer   $id
 * @property integer   $groupsMenuId
 * @property string    $title
 * @property integer   $sort
 * @property integer   $pageId
 * @property string    $link
 * @property GroupMenu $group
 * @property Page      $page
 */
class MenuItem extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'MenuItems';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['groupsMenuId', 'sort', 'pageId'], 'integer'],
			[['title', 'link'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'groupsMenuId' => 'Группа',
			'title'        => 'Название',
			'sort'         => 'Сортировка',
			'pageId'       => 'Страница',
			'link'         => 'Ссылка',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				if ($this->groupsMenuId) {
					$this->sort = self::find()->where(['groupsMenuId' => $this->groupsMenuId])->max('sort') + 1;
				} else {
					$this->sort = GroupMenu::find()->max('sort') + 1;
				}
			}
		}
		
		return parent::beforeValidate();
	}
	
	public function getGroup()
	{
		return $this->hasOne(GroupMenu::className(), ['id' => 'groupsMenuId']);
	}
	
	public function getPage()
	{
		return $this->hasOne(Page::className(), ['id' => 'pageId']);
	}
}
