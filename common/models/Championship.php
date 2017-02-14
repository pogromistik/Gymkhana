<?php

namespace common\models;

use common\models\RegionalGroup;
use Yii;

/**
 * This is the model class for table "championships".
 *
 * @property integer         $id
 * @property string          $title
 * @property string          $description
 * @property integer         $yearId
 * @property integer         $status
 * @property integer         $groupId
 * @property integer         $regionGroupId
 * @property integer         $dateAdded
 * @property integer         $dateUpdated
 * @property integer         $regionId
 * @property integer         $minNumber
 * @property integer         $maxNumber
 *
 * @property Year            $year
 * @property RegionalGroup   $regionalGroup
 * @property Stage[]         $stages
 * @property InternalClass[] $internalClasses
 * @property Region          $region
 */
class Championship extends \yii\db\ActiveRecord
{
	const STATUS_UPCOMING = 1;
	const STATUS_PAST = 2;
	const STATUS_PRESENT = 3;
	
	public static $statusesForActual = [
		self::STATUS_UPCOMING,
		self::STATUS_PRESENT
	];
	
	const GROUPS_RUSSIA = 1;
	const GROUPS_REGIONAL = 2;
	
	public static $statusesTitle = [
		self::STATUS_UPCOMING => 'Предстоящий чемпионат',
		self::STATUS_PAST     => 'Прошедший чемпионат',
		self::STATUS_PRESENT  => 'Текущий чемпионат'
	];
	
	public static $groupsTitle = [
		self::GROUPS_RUSSIA   => 'Чемпионаты России',
		self::GROUPS_REGIONAL => 'Региональные чемпионаты'
	];
	
	public function init()
	{
		parent::init();
		if ($this->isNewRecord) {
			if (!$this->minNumber) {
				$this->minNumber = 1;
			};
			if (!$this->maxNumber) {
				$this->maxNumber = 99;
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Championships';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['description'], 'string'],
			[['yearId', 'groupId', 'dateAdded', 'dateUpdated', 'minNumber', 'maxNumber'], 'required'],
			[[
				'yearId', 'status', 'groupId', 'regionGroupId',
				'dateAdded', 'dateUpdated', 'regionId'
			], 'integer'],
			['regionGroupId', 'required', 'when' => function ($model) {
				return $model->groupId == self::GROUPS_REGIONAL;
			}],
			[['title'], 'string', 'max' => 255],
			[['minNumber', 'maxNumber'], 'integer', 'min' => 0],
			['minNumber', 'default', 'value' => 1],
			['maxNumber', 'default', 'value' => 99]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'title'         => 'Название',
			'description'   => 'Описание',
			'yearId'        => 'Год проведения',
			'status'        => 'Статус',
			'groupId'       => 'Раздел',
			'regionGroupId' => 'Региональный раздел',
			'dateAdded'     => 'Дата создания',
			'dateUpdated'   => 'Дата редактирования',
			'regionId'      => 'Регион проведения чемпионата',
			'minNumber'     => 'Минимальный номер участника',
			'maxNumber'     => 'Максимальный номер участника'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
		}
		$this->dateUpdated = time();
		
		if (!$this->title) {
			switch ($this->groupId) {
				case self::GROUPS_RUSSIA:
					$this->title = 'Чемпионат России ' . $this->year->year;
					break;
				case self::GROUPS_REGIONAL:
					if ($this->regionGroupId) {
						$this->title = $this->regionalGroup->title . ' ' . $this->year->year;
					}
					break;
			}
		}
		
		if ($this->minNumber > $this->maxNumber) {
			$max = $this->minNumber;
			$this->minNumber = $this->maxNumber;
			$this->maxNumber = $max;
		}
		
		return parent::beforeValidate();
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		if ($insert) {
			AssocNews::createStandardNews(AssocNews::TEMPLATE_CHAMPIONSHIP, $this);
		}
	}
	
	public function getYear()
	{
		return $this->hasOne(Year::className(), ['id' => 'yearId']);
	}
	
	public function getRegionalGroup()
	{
		return $this->hasOne(RegionalGroup::className(), ['id' => 'regionGroupId']);
	}
	
	public function getStages()
	{
		return $this->hasMany(Stage::className(), ['championshipId' => 'id'])->orderBy(['dateOfThe' => SORT_DESC, 'dateAdded' => SORT_ASC]);
	}
	
	public function getInternalClasses()
	{
		return $this->hasMany(InternalClass::className(), ['championshipId' => 'id']);
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
}