<?php

namespace common\models;

use common\helpers\UserHelper;
use Yii;
use yii\bootstrap\Html;

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
 * @property integer $canEditRegionId
 * @property integer $creatorUserId
 */
class AssocNews extends \yii\db\ActiveRecord
{
	const TEMPLATE_CHAMPIONSHIP = 1;
	const TEMPLATE_STAGE = 2;
	
	public $datePublishHuman;
	public $autoCreate = false;
	
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
			[['dateAdded', 'dateUpdated', 'datePublish', 'secure', 'canEditRegionId', 'creatorUserId'], 'integer'],
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
			$this->creatorUserId = UserHelper::getUserId();
			if (!$this->autoCreate && $this->creatorUserId != UserHelper::CONSOLE_LOG_USER_ID) {
				$user = User::findOne($this->creatorUserId);
				$this->canEditRegionId = $user->regionId;
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
		$news = new AssocNews();
		$news->autoCreate = true;
		switch ($template) {
			case self::TEMPLATE_CHAMPIONSHIP:
				/** @var Championship $championship */
				$championship = $model;
				$news->title = $championship->title;
				if ($championship->isClosed) {
					$news->previewText = 'Анонсирован закрытый чемпионат "' . $championship->title . '".';
				} else {
					$news->previewText = 'Анонсирован чемпионат "' . $championship->title . '".';
				}
				
				if ($championship->regionId) {
					$news->previewText .= ' Регион проведения: ' . $championship->region->title . '.';
					$news->canEditRegionId = $championship->regionId;
				}
				$fullText = 'В ' . $championship->year->year . ' году пройдёт ' . $championship->title . '.<br>';
				if ($championship->description) {
					$fullText .= $championship->description;
					$fullText .= '<br>';
				}
				$regionsFor = $championship->getRegionsFor(true);
				if ($regionsFor && $championship->isClosed) {
					$fullText .= 'Регионы, допускающиеся к участию: ' . $regionsFor . '<br><br>';
				}
				$fullText .= 'Обязательное количество этапов для спортсмена: ' . $championship->amountForAthlete;
				$fullText .= '<br>';
				$fullText .= 'Количество этапов, по которым подсчитывается итог: ' . $championship->estimatedAmount;
				if ($championship->requiredOtherRegions) {
					$fullText .= '<br>';
					$fullText .= 'Для полноценного участия в чемпионате необходимо хоть раз выступить на этапе в другом городе';
				}
				$fullText .= '<br>';
				$fullText .= 'Диапазон стартовых номеров участников: ' . $championship->minNumber . '-' . $championship->maxNumber;
				if ($championship->activeInternalClasses) {
					$classes = [];
					foreach ($championship->activeInternalClasses as $class) {
						$classes[] = $class->title;
					}
					$fullText .= '<br>';
					$fullText .= 'Классы награждения: ' . implode(', ', $classes);
				}
				$fullText .= '<br>';
				$fullText .= 'Информация может меняться, чтобы узнать подробнее о чемпионате пройдите по ' .
					Html::a('ссылке', ['/competitions/championship', 'id' => $championship->id]) . '.';
				$news->fullText = $fullText;
				break;
			case self::TEMPLATE_STAGE:
				/** @var Stage $stage */
				$stage = $model;
				$news->canEditRegionId = $stage->regionId;
				$news->previewText = $stage->title . ' соревнования "'
					. $stage->championship->title . '" пройдёт в городе ' . $stage->city->title;
				if ($stage->location) {
					$news->previewText .= '.<br>Место проведения этапа: ' . $stage->location;
				}
				if ($stage->dateOfThe) {
					$news->previewText .= '.<br>Дата проведения: ' . $stage->dateOfTheHuman;
				}
				if ($stage->startRegistration) {
					$news->previewText .= '.<br>Начало регистрации: ' . $stage->startRegistrationHuman;
				}
				$news->previewText .= '.';
				$news->link = \Yii::$app->urlManager->createUrl(['/competitions/stage', 'id' => $stage->id]);
				break;
		}
		$news->save();
		
		return true;
	}
}
