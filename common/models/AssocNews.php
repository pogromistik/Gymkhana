<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "assoc_news".
 *
 * @property integer $id
 * @property string  $title
 * @property string  $previewText
 * @property string  $fullText
 * @property string  $link
 * @property integer $dateAdded
 * @property integer $dateUpdated
 * @property integer $datePublish
 * @property integer $secure
 */
class AssocNews extends \yii\db\ActiveRecord
{
	const TEMPLATE_CHAMPIONSHIP = 1;
	const TEMPLATE_STAGE = 2;
	
	public $datePublishHuman;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'AssocNews';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['fullText', 'previewText'], 'string'],
			[['dateAdded', 'dateUpdated', 'previewText'], 'required'],
			[['dateAdded', 'dateUpdated', 'datePublish', 'secure'], 'integer'],
			[['previewText', 'link', 'title', 'datePublishHuman'], 'string', 'max' => 255],
			[['secure'], 'default', 'value' => 0]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'title'            => 'Заголовок',
			'previewText'      => 'Короткий текст',
			'fullText'         => 'Подробный текст',
			'link'             => 'Ссылка на страницу',
			'dateAdded'        => 'Дата создания',
			'dateUpdated'      => 'Дата добавления',
			'datePublish'      => 'Дата публикации',
			'datePublishHuman' => 'Дата публикации',
			'secure'           => 'Закрепить сверху'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if (!$this->datePublish) {
				$this->datePublish = time();
			}
		}
		$this->dateUpdated = time();
		
		if ($this->datePublishHuman) {
			$this->datePublish = (new \DateTime($this->datePublishHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->datePublish) {
			$this->datePublishHuman = date('d.m.Y', $this->datePublish);
		}
	}
	
	public static function createStandardNews($template, $model)
	{
		switch ($template) {
			case self::TEMPLATE_CHAMPIONSHIP:
				/** @var Championship $championship */
				$championship = $model;
				$news = new AssocNews();
				$news->previewText = 'Анонсирован ' . $championship->title . '.';
				if ($championship->regionId) {
					$news->previewText .= ' Регион проведения: ' . $championship->region->title . '.';
				}
				if ($championship->description) {
					$news->fullText = $championship->description;
				}
				$news->save();
				break;
			case self::TEMPLATE_STAGE:
				/** @var Stage $stage */
				$stage = $model;
				$news = new AssocNews();
				$news->previewText = $stage->title . ' пройдёт в городе ' . $stage->city->title;
				if ($stage->location) {
					$news->previewText .= '.<br>Место проведения этапа: ' . $stage->location;
				}
				if ($stage->dateOfThe) {
					$news->previewText .= '.<br>Дата проведения: ' . $stage->dateOfTheHuman;
				}
				if ($stage->startRegistration) {
					$news->previewText .= '.<br>Начало регистрации: ' . $stage->startRegistrationHuman;
				}
				$news->previewText .='.';
				$news->save();
				break;
			
		}
		
		return true;
	}
}
