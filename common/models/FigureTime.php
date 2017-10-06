<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "FigureTimes".
 *
 * @property integer       $id
 * @property integer       $figureId
 * @property integer       $athleteId
 * @property integer       $motorcycleId
 * @property integer       $yearId
 * @property integer       $athleteClassId
 * @property integer       $newAthleteClassId
 * @property integer       $newAthleteClassStatus
 * @property integer       $date
 * @property double        $percent
 * @property integer       $time
 * @property integer       $fine
 * @property integer       $dateAdded
 * @property integer       $dateUpdated
 * @property integer       $resultTime
 * @property integer       $recordType
 * @property integer       $recordStatus
 * @property integer       $recordInMoment
 * @property string        $videoLink
 * @property double        $actualPercent
 * @property integer       $stageId
 *
 * @property Athlete       $athlete
 * @property Motorcycle    $motorcycle
 * @property AthletesClass $athleteClass
 * @property AthletesClass $newAthleteClass
 * @property Figure        $figure
 * @property Year          $year
 */
class FigureTime extends BaseActiveRecord
{
	protected static $enableLogging = true;
	
	const NEW_CLASS_STATUS_NEED_CHECK = 1;
	const NEW_CLASS_STATUS_APPROVE = 2;
	const NEW_CLASS_STATUS_CANCEL = 3;
	
	public $timeForHuman;
	public $resultTimeForHuman;
	public $dateForHuman;
	public $recordInMomentHuman;
	
	public $needClassCalculate = true;
	
	const RECORD_IN_RUSSIA = 1;
	const RECORD_IN_WORLD = 2;
	public static $recordsTitle = [
		self::RECORD_IN_RUSSIA => 'Новый Российский рекорд',
		self::RECORD_IN_WORLD  => 'Новый мировой рекорд'
	];
	
	const NEW_RECORD_NEED_CHECK = 1;
	const NEW_RECORD_APPROVE = 2;
	const NEW_RECORD_CANCEL = 3;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'FigureTimes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['figureId', 'athleteId', 'motorcycleId', 'yearId', 'date',
				'percent', 'timeForHuman', 'dateAdded', 'dateUpdated', 'resultTime'], 'required'],
			[['figureId', 'athleteId', 'motorcycleId', 'yearId', 'athleteClassId',
				'newAthleteClassId', 'newAthleteClassStatus', 'date', 'time', 'fine', 'dateAdded',
				'dateUpdated', 'resultTime', 'recordType', 'recordStatus', 'recordInMoment', 'stageId'], 'integer'],
			[['dateForHuman', 'timeForHuman', 'videoLink'], 'string'],
			[['percent', 'actualPercent'], 'number'],
			[['fine'], 'default', 'value' => 0],
			['videoLink', 'validateVideoLink']
		];
	}
	
	public function validateVideoLink($attribute, $params)
	{
		if (!$this->hasErrors() && $this->videoLink) {
			if (mb_strstr($this->videoLink, 'http://', 'UTF-8') === false
				&& mb_strstr($this->videoLink, 'https://', 'UTF-8') === false
			) {
				$this->addError($attribute, 'Ссылка на видео должна содержать http:// или https://');
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                    => 'ID',
			'figureId'              => 'Фигура',
			'athleteId'             => 'Спортсмен',
			'motorcycleId'          => 'Мотоцикл',
			'yearId'                => 'Год',
			'athleteClassId'        => 'Класс спортсмена',
			'newAthleteClassId'     => 'Новый класс',
			'newAthleteClassStatus' => 'New Athlete Class Status',
			'date'                  => 'Дата проезда',
			'dateForHuman'          => 'Дата проезда',
			'percent'               => 'Рейтинг',
			'time'                  => 'Время',
			'timeForHuman'          => 'Время',
			'fine'                  => 'Штраф',
			'dateAdded'             => 'Дата добавления',
			'dateUpdated'           => 'Дата редактирования',
			'resultTime'            => 'Итоговое время',
			'resultTimeForHuman'    => 'Итоговое время',
			'recordInMoment'        => 'Эталонное время на тот момент',
			'videoLink'             => 'Видео',
			'actualPercent'         => 'Актуальный рейтинг',
			'stageId'               => 'Этап, на котором установлен результат'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			$athlete = Athlete::findOne($this->athleteId);
			$this->athleteClassId = $athlete->athleteClassId;
		}
		if ($this->timeForHuman) {
			list($min, $secs) = explode(':', $this->timeForHuman);
			$this->time = ($min * 60000) + round($secs * 1000);
		}
		$this->resultTime = $this->time;
		if ($this->fine) {
			$this->resultTime += $this->fine * 1000;
		}
		if ($this->dateForHuman) {
			$this->date = (new \DateTime($this->dateForHuman, new \DateTimeZone('Asia/Yekaterinburg')))->getTimestamp();
		}
		
		$yearTitle = date("Y", $this->date);
		$year = Year::findOne(['year' => $yearTitle]);
		if (!$year) {
			$year = new Year();
			$year->year = $yearTitle;
			$year->status = Year::STATUS_ACTIVE;
			$year->save();
		}
		$this->yearId = $year->id;
		
		$this->dateUpdated = time();
		
		$figure = Figure::findOne($this->figureId);
		if ($figure->bestTime) {
			$this->percent = round($this->resultTime / $figure->bestTime * 100, 2);
			if ($this->isNewRecord) {
				$this->recordInMoment = $figure->bestTime;
			}
		} else {
			$this->percent = 100;
		}
		if (!$this->actualPercent) {
			$this->actualPercent = $this->percent;
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		if ($this->isNewRecord || $this->isAttributeChanged('percent')) {
			$figure = Figure::findOne($this->figureId);
			if ($figure->useForClassesCalculate && $this->percent && $this->needClassCalculate) {
				//Рассчёт класса
				$this->newAthleteClassId = null;
				$this->newAthleteClassStatus = null;
				/** @var AthletesClass $newClass */
				$newClass = AthletesClass::find()->where(['>', 'percent', $this->percent])
					->andWhere(['status' => AthletesClass::STATUS_ACTIVE])
					->orderBy(['percent' => SORT_ASC, 'title' => SORT_DESC])->one();
				if ($newClass && $newClass->id != $this->athleteClassId) {
					if ($this->athleteClassId) {
						$oldClass = $this->athleteClass;
						if ($oldClass->id != $newClass->id && $oldClass->percent > $newClass->percent) {
							$this->newAthleteClassId = $newClass->id;
							$this->newAthleteClassStatus = self::NEW_CLASS_STATUS_NEED_CHECK;
						}
					} else {
						$this->newAthleteClassId = $newClass->id;
						$this->newAthleteClassStatus = self::NEW_CLASS_STATUS_NEED_CHECK;
					}
				}
			}
			
			if (!$figure->bestTime || $this->resultTime < $figure->bestTime) {
				$this->recordType = self::RECORD_IN_WORLD;
				$this->recordStatus = self::NEW_RECORD_NEED_CHECK;
			} elseif (!$figure->bestTimeInRussia || $this->resultTime < $figure->bestTimeInRussia) {
				$athlete = $this->athlete;
				if (mb_strtoupper($athlete->country->title, 'UTF-8') == 'РОССИЯ') {
					$this->recordType = self::RECORD_IN_RUSSIA;
					$this->recordStatus = self::NEW_RECORD_NEED_CHECK;
				}
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->time) {
			$min = str_pad(floor($this->time / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->time - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->time - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->timeForHuman = $min . ':' . $sec . '.' . $mls;
		}
		
		if ($this->recordInMoment) {
			$this->recordInMomentHuman = HelpModel::convertTimeToHuman($this->recordInMoment);
		}
		
		if ($this->resultTime) {
			$min = str_pad(floor($this->resultTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->resultTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->resultTime - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->resultTimeForHuman = $min . ':' . $sec . '.' . $mls;
		}
		
		if ($this->date) {
			$this->dateForHuman = date('d.m.Y', $this->date);
		}
	}
	
	/*public function beforeSave($insert)
	{
		if (array_key_exists('newAthleteClassId', $this->dirtyAttributes)
			&& $this->dirtyAttributes['newAthleteClassId']
		) {
			$athlete = $this->athlete;
			if ($athlete->athleteClassId != $this->dirtyAttributes['newAthleteClassId']) {
				$event = $this->figure->title;
				ClassHistory::create($athlete->id, $this->motorcycleId,
					$athlete->athleteClassId, $this->newAthleteClassId, $event,
					$this->resultTime, $this->figure->bestTime, $this->percent);
				
				$athlete->athleteClassId = $this->newAthleteClassId;
				$athlete->save(false);
			}
		}
		return parent::beforeSave($insert);
	}*/
	
	public function getFigure()
	{
		return $this->hasOne(Figure::className(), ['id' => 'figureId']);
	}
	
	public function getAthlete()
	{
		return $this->hasOne(Athlete::className(), ['id' => 'athleteId']);
	}
	
	public function getMotorcycle()
	{
		return $this->hasOne(Motorcycle::className(), ['id' => 'motorcycleId']);
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public function getNewAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'newAthleteClassId']);
	}
	
	public function getYear()
	{
		return $this->hasOne(Year::className(), ['id' => 'yearId']);
	}
}
