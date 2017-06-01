<?php

namespace common\models;

use common\components\BaseActiveRecord;
use common\models\RegionalGroup;
use Yii;
use yii\db\Expression;
use yii\db\Query;

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
 * @property integer         $amountForAthlete
 * @property integer         $requiredOtherRegions
 * @property integer         $estimatedAmount
 * @property integer         $isClosed
 * @property string          $onlyRegions
 * @property integer         $useCheScheme
 *
 * @property Year            $year
 * @property RegionalGroup   $regionalGroup
 * @property Stage[]         $stages
 * @property InternalClass[] $internalClasses
 * @property InternalClass[] $activeInternalClasses
 * @property Region          $region
 */
class Championship extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
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
			if (!$this->amountForAthlete) {
				$this->amountForAthlete = 1;
			}
			if (!$this->requiredOtherRegions) {
				$this->requiredOtherRegions = 0;
			}
			if (!$this->estimatedAmount) {
				$this->estimatedAmount = 1;
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
			[['yearId', 'groupId', 'dateAdded', 'dateUpdated', 'minNumber', 'maxNumber',
				'amountForAthlete', 'requiredOtherRegions', 'estimatedAmount'], 'required'],
			[[
				'yearId', 'status', 'groupId', 'regionGroupId',
				'dateAdded', 'dateUpdated', 'regionId',
				'amountForAthlete', 'requiredOtherRegions', 'estimatedAmount',
				'isClosed', 'useCheScheme'
			], 'integer'],
			['regionGroupId', 'required', 'when' => function ($model) {
				return $model->groupId == self::GROUPS_REGIONAL;
			}],
			[['title'], 'string', 'max' => 255],
			[['minNumber', 'maxNumber'], 'integer', 'min' => 0],
			[['amountForAthlete', 'estimatedAmount'], 'integer', 'min' => 1],
			[['minNumber', 'amountForAthlete', 'estimatedAmount'], 'default', 'value' => 1],
			['maxNumber', 'default', 'value' => 99],
			[['requiredOtherRegions', 'isClosed', 'useCheScheme'], 'default', 'value' => 0],
			[['onlyRegions'], 'safe'],
			['useCheScheme', 'validateClasses']
		];
	}
	
	public function validateClasses($attribute, $params)
	{
		if (!$this->hasErrors() && $this->useCheScheme) {
			$classes = $this->getInternalClasses()->count();
			$classesWithoutScheme = $this->getInternalClasses()->andWhere(['not', ['cheId' => null]])->count();
			if ($classes != $classesWithoutScheme) {
				$this->addError($attribute, 'Вы не можете использовать эту схему, т.к. у вас созданы классы, 
				не относящиеся к ней.');
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                   => 'ID',
			'title'                => 'Название',
			'description'          => 'Описание',
			'yearId'               => 'Год проведения',
			'status'               => 'Статус',
			'groupId'              => 'Раздел',
			'regionGroupId'        => 'Региональный раздел',
			'dateAdded'            => 'Дата создания',
			'dateUpdated'          => 'Дата редактирования',
			'regionId'             => 'Регион проведения чемпионата',
			'minNumber'            => 'Минимальный номер участника',
			'maxNumber'            => 'Максимальный номер участника',
			'amountForAthlete'     => 'Обязательное количество этапов для спортсмена',
			'requiredOtherRegions' => 'Необходимо хоть раз выступить в другом городе',
			'estimatedAmount'      => 'Количество этапов, по которым подсчитывается итог',
			'isClosed'             => 'Закрытый чемпионат',
			'onlyRegions'          => 'Регионы, которые могут принимать участие',
			'useCheScheme'         => 'Использовать Челябинскую схему для награждения'
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
		
		if (!$this->isClosed) {
			$this->onlyRegions = null;
		}
		
		if ($this->minNumber > $this->maxNumber) {
			$max = $this->minNumber;
			$this->minNumber = $this->maxNumber;
			$this->maxNumber = $max;
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		if ($this->onlyRegions) {
			$this->onlyRegions = json_encode($this->onlyRegions);
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->onlyRegions) {
			$this->onlyRegions = json_decode($this->onlyRegions, true);
		}
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		if ($insert) {
			AssocNews::createStandardNews(AssocNews::TEMPLATE_CHAMPIONSHIP, $this);
		}
		if (array_key_exists('useCheScheme', $changedAttributes)) {
			if ($this->useCheScheme) {
				$oldIds = $this->getInternalClasses()->select('cheId')->andWhere(['not', ['cheId' => null]])->asArray()->column();
				if ($oldIds) {
					$cheItems = CheScheme::find()->where(['not', ['id' => $oldIds]])->all();
				} else {
					$cheItems = CheScheme::find()->all();
				}
				/** @var CheScheme $item */
				foreach ($cheItems as $item) {
					$class = new InternalClass();
					$class->title = $item->title;
					$class->championshipId = $this->id;
					$class->description = $item->description;
					$class->cheId = $item->id;
					$class->save();
				}
			} else {
				$oldIds = $this->getInternalClasses()->select('id')->asArray()->column();
				if (!Participant::findOne(['championshipId' => $this->id, 'internalClassId' => $oldIds])) {
					InternalClass::deleteAll(['id' => $oldIds]);
				}
			}
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
		return $this->hasMany(Stage::className(), ['championshipId' => 'id'])->orderBy(['dateOfThe' => SORT_ASC, 'dateAdded' => SORT_ASC]);
	}
	
	public function getInternalClasses()
	{
		return $this->hasMany(InternalClass::className(), ['championshipId' => 'id']);
	}
	
	public function getActiveInternalClasses()
	{
		return $this->hasMany(InternalClass::className(), ['championshipId' => 'id'])->andOnCondition(['status' => InternalClass::STATUS_ACTIVE]);
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
	
	/**
	 * @param Stage    $stage
	 * @param int|null $athleteId
	 *
	 * @return array
	 */
	public static function getFreeNumbers($stage, $athleteId = null)
	{
		$championship = $stage->championship;
		$numbers = [];
		for ($i = $championship->minNumber; $i <= $championship->maxNumber; $i++) {
			$numbers[] = $i;
		}
		
		$busyNumbers = Participant::find()->select('number')->where(['stageId' => $stage->id]);
		if ($athleteId) {
			$busyNumbers = $busyNumbers->andWhere(['!=', 'athleteId', $athleteId]);
		}
		$busyNumbers = $busyNumbers->asArray()->column();
		$addOldNumber = null;
		if ($championship->regionId && $stage->status != Stage::STATUS_PAST) {
			$query = new Query();
			$query->from([Athlete::tableName(), City::tableName(), Region::tableName()]);
			$query->select('Athletes."number"');
			$query->where(['Regions."id"' => $championship->regionId]);
			if ($athleteId) {
				$query->andWhere(['not', ['Athletes.id' => $athleteId]]);
			}
			$query->andWhere(new Expression('"Athletes"."cityId" = "Cities"."id"'));
			$query->andWhere(new Expression('"Cities"."regionId" = "Regions"."id"'));
			if ($athleteId) {
				$query->andWhere(['!=', 'Athletes."id"', $athleteId]);
			}
			$busyNumbersForAthletes = $query->column();
			$busyNumbers = array_merge($busyNumbers, $busyNumbersForAthletes);
			if ($athleteId) {
				$athlete = Athlete::findOne($athleteId);
				if ($athlete->regionId == $championship->regionId && !in_array($athlete->number, $numbers)) {
					$numbers[] = $athlete->number;
				}
				//если участник уже принимал участие в этапе - он может зарегистрироваться под этим же номером
				$prevStages = Participant::find()->where(['championshipId' => $championship->id])
					->andWhere(['athleteId' => $athleteId])
					->andWhere(['status' => Participant::STATUS_ACTIVE])
					->one();
				if ($prevStages) {
					$addOldNumber = $prevStages->number;
				}
			}
		}
		$tmpBusyNumbers = TmpParticipant::find()->select('number')->where(['stageId' => $stage->id])
			->andWhere(['status' => TmpParticipant::STATUS_NEW])->asArray()->column();
		$busyNumbers = array_merge($busyNumbers, $tmpBusyNumbers);
		$oldParticipantsNumbers = Participant::find()->select('number')
			->where(['not', ['stageId' => $stage->id]])
			->andWhere(['championshipId' => $stage->championshipId]);
		if ($athleteId) {
			$oldParticipantsNumbers = $oldParticipantsNumbers->andWhere(['!=', 'athleteId', $athleteId]);
		}
		$oldParticipantsNumbers = $oldParticipantsNumbers->asArray()->column();
		$busyNumbers = array_merge($busyNumbers, $oldParticipantsNumbers);
		
		foreach ($numbers as $i => $number) {
			if (in_array($number, $busyNumbers)) {
				unset($numbers[$i]);
			}
		}
		
		if ($addOldNumber) {
			$numbers[] = $addOldNumber;
		}
		
		return $numbers;
	}
	
	public function getResults($showAll = false)
	{
		$stages = $this->stages;
		$results = [];
		foreach ($stages as $stage) {
			/** @var Participant[] $participants */
			$participants = Participant::find()->where(['stageId' => $stage->id])->andWhere(['status' => Participant::STATUS_ACTIVE])
				->orderBy(['points' => SORT_DESC, 'sort' => SORT_ASC])->all();
			foreach ($participants as $participant) {
				if (!isset($results[$participant->athleteId])) {
					$results[$participant->athleteId] = [
						'athlete'        => $participant->athlete,
						'points'         => 0,
						'stages'         => [],
						'countStages'    => 0,
						'cityId'         => null,
						'severalRegions' => false
					];
				}
				if (!isset($results[$participant->athleteId]['stages'][$stage->id])) {
					$results[$participant->athleteId]['stages'][$stage->id] = $participant->points;
					$results[$participant->athleteId]['points'] += $participant->points;
					$results[$participant->athleteId]['countStages'] += 1;
					if (!$results[$participant->athleteId]['cityId']) {
						$results[$participant->athleteId]['cityId'] = $stage->cityId;
					} else {
						if ($stage->cityId != $results[$participant->athleteId]['cityId']) {
							$results[$participant->athleteId]['severalRegions'] = true;
						}
					}
				}
			}
		}
		
		if (!$showAll) {
			foreach ($results as $i => $result) {
				if ($result['countStages'] < $this->amountForAthlete) {
					unset($results[$i]);
					continue;
				}
				if ($this->requiredOtherRegions && !$result['severalRegions']) {
					unset($results[$i]);
					continue;
				}
				if (count($result['stages']) != $this->estimatedAmount) {
					$allPoints = $result['stages'];
					arsort($allPoints);
					$count = 0;
					$result['points'] = 0;
					foreach ($allPoints as $stagePoint) {
						if ($count < $this->estimatedAmount) {
							$result['points'] += $stagePoint;
							$count++;
						} else {
							break;
						}
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
	
	public function getInternalClassesTitle($asArray = false)
	{
		$classes = [];
		foreach ($this->internalClasses as $class) {
			$classes[] = $class->title;
		}
		if ($asArray) {
			return $classes;
		}
		
		if ($classes) {
			return implode(', ', $classes);
		}
		
		return null;
	}
	
	/**
	 * @return null|Region[]
	 */
	public function getRegionsFor($asString = false, $asArray = false)
	{
		if (!$this->onlyRegions) {
			return null;
		}
		
		if (!is_array($this->onlyRegions)) {
			$this->onlyRegions = json_decode($this->onlyRegions, true);
		}
		
		if ($asArray) {
			return Region::find()->select('id')->where(['id' => $this->onlyRegions])->asArray()->column();
		}
		
		$result = Region::findAll($this->onlyRegions);
		if (!$result) {
			return null;
		}
		if ($asString) {
			$regions = [];
			foreach ($result as $region) {
				$regions[] = $region->title;
			}
			
			return implode(', ', $regions);
		}
		
		return $result;
	}
	
	public function checkAccessForRegion($regionId)
	{
		if (!$this->isClosed || !$this->onlyRegions) {
			return true;
		}
		$regions = $this->getRegionsFor(false, true);
		if (!$regions) {
			return true;
		}
		
		return in_array($regionId, $regions);
	}
}
