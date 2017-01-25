<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property integer     $id
 * @property string      $title
 * @property integer     $dateCreated
 * @property integer     $datePublish
 * @property integer     $dateUpdated
 * @property string      $previewText
 * @property string      $previewImage
 * @property integer     $isPublish
 * @property integer     $pageId
 * @property integer     $secure
 * @property NewsBlock[] $newsBlock
 * @property Page        $page
 */
class News extends \yii\db\ActiveRecord
{
	public $file;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'news';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'dateCreated', 'datePublish', 'dateUpdated', 'isPublish'], 'required'],
			[['dateCreated', 'datePublish', 'dateUpdated', 'isPublish', 'pageId', 'secure'], 'integer'],
			[['previewText'], 'string'],
			[['title', 'previewImage'], 'string', 'max' => 255],
			[['file'], 'file', 'extensions' => 'png, jpg'],
			[['isPublish'], 'default', 'value' => 1],
			[['secure'], 'default', 'value' => 0]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'title'        => 'Название',
			'dateCreated'  => 'Дата создания',
			'datePublish'  => 'Дата публикации',
			'dateUpdated'  => 'Дата обновления',
			'previewText'  => 'Preview Text',
			'previewImage' => 'Preview Image',
			'isPublish'    => 'Опубликовать',
			'file'         => 'Preview image',
			'secure'       => 'Закрепить сверху'
		];
	}
	
	public function beforeSave($insert)
	{
		if ($this->isNewRecord) {
			$page = new Page();
			$page->title = $this->title;
			$page->layoutId = 'news';
			$page->parentId = Page::findOne(['layoutId' => 'allNews'])->id;
			$page->save();
			
			$this->pageId = $page->id;
		}
		
		return parent::beforeSave($insert);
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateCreated = time();
		}
		if ($this->isPublish) {
			$this->datePublish = time();
		}
		$this->dateUpdated = time();
		
		return parent::beforeValidate();
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		if (in_array('title', $changedAttributes)) {
			$page = $this->page;
			$page->title = $this->title;
			$page->save(false);
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function getPage()
	{
		return $this->hasOne(Page::className(), ['id' => 'pageId']);
	}
	
	public function getNewsBlock()
	{
		return $this->hasMany(NewsBlock::className(), ['newsId' => 'id']);
	}
}
