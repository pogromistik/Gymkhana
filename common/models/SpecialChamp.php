<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "SpecialChamps".
 *
 * @property integer        $id
 * @property string         $title
 * @property string         $description
 * @property integer        $yearId
 * @property integer        $status
 * @property integer        $dateAdded
 * @property integer        $dateUpdated
 * @property string         $title_en
 * @property string         $descr_en
 *
 * @property Year           $year
 * @property SpecialStage[] $stages
 */
class SpecialChamp extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_PRESENT = 3;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING => 'Предстоящий чемпионат',
		self::STATUS_PAST     => 'Прошедший чемпионат',
		self::STATUS_PRESENT  => 'Текущий чемпионат'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'SpecialChamps';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'yearId', 'dateAdded', 'dateUpdated'], 'required'],
			[['description', 'descr_en'], 'string'],
			[['yearId', 'status', 'dateAdded', 'dateUpdated'], 'integer'],
			[['title', 'title_en'], 'string', 'max' => 255],
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
			'description' => 'Описание',
			'yearId'      => 'Год проведения',
			'status'      => 'Статус',
			'dateAdded'   => 'Дата добавления',
			'dateUpdated' => 'Дата редактирования',
			'title_en'    => 'Название',
			'descr_en'    => 'Описание'
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
	
	public function getYear()
	{
		return $this->hasOne(Year::className(), ['id' => 'yearId']);
	}
	
	public function getStages()
	{
		return $this->hasMany(SpecialStage::className(), ['championshipId' => 'id'])->orderBy(['dateResult' => SORT_ASC, 'dateAdded' => SORT_ASC]);
	}
	
	public function getResults()
	{
		$stages = $this->stages;
		$results = [];
		foreach ($stages as $stage) {
			/** @var RequestForSpecialStage[] $requests */
			$requests = RequestForSpecialStage::find()->where(['stageId' => $stage->id])->andWhere([
				'status' => RequestForSpecialStage::STATUS_APPROVE])
				->orderBy(['points' => SORT_DESC])->all();
			foreach ($requests as $request) {
				if (!isset($results[$request->athleteId])) {
					$results[$request->athleteId] = [
						'athlete' => $request->athlete,
						'points'  => 0,
						'stages'  => []
					];
				}
				if (!isset($results[$request->athleteId]['stages'][$stage->id])) {
					$results[$request->athleteId]['stages'][$stage->id] = $request->points;
					if (!$stage->outOfCompetitions) {
						$results[$request->athleteId]['points'] += $request->points;
					}
				}
			}
		}
		
		uasort($results, "self::cmpByRackPlaces");
		
		return $results;
	}
	
	private function cmpByRackPlaces($a, $b)
	{
		return ($a['points'] > $b['points']) ? -1 : 1;
	}
	
	public function getTitle($language = null)
	{
		if (!$this->title_en) {
			return $this->title;
		}
		if (!$language) {
			$language = \Yii::$app->language;
		}
		switch ($language) {
			case TranslateMessage::LANGUAGE_EN:
				return $this->title_en;
			case TranslateMessage::LANGUAGE_RU:
				return $this->title;
			default:
				return $this->title_en;
		}
	}
	
	public function getDescr($language = null)
	{
		if (!$this->descr_en) {
			return $this->description;
		}
		if (!$language) {
			$language = \Yii::$app->language;
		}
		switch ($language) {
			case TranslateMessage::LANGUAGE_EN:
				return $this->descr_en;
			case TranslateMessage::LANGUAGE_RU:
				return $this->description;
			default:
				return $this->descr_en;
		}
	}
}
