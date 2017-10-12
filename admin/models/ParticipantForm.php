<?php
/**
 * Created by PhpStorm.
 * User: Nadia
 * Date: 18.02.2016
 * Time: 11:59
 */

namespace admin\models;

use common\models\Athlete;
use common\models\HelpModel;
use common\models\RequestForSpecialStage;
use yii\base\Model;

/**
 * Signup form
 */
class ParticipantForm extends Model
{
	public $athleteId;
	public $motorcycleId;
	public $timeHuman;
	public $fine;
	public $videoLink;
	public $date;
	public $dateHuman;
	public $time;
	public $stageId;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['athleteId', 'motorcycleId', 'timeHuman', 'fine', 'videoLink', 'dateHuman', 'stageId'], 'required'],
			[['athleteId', 'motorcycleId', 'fine', 'stageId'], 'integer'],
			[['dateHuman', 'timeHuman', 'videoLink'], 'string']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'athleteId'    => 'Спортсмен',
			'motorcycleId' => 'Мотоцикл',
			'timeHuman'    => 'Время без учёта штрафа',
			'fine'         => 'Штраф',
			'videoLink'    => 'Ссылка на видео',
			'dateHuman'    => 'Дата заезда',
			'stageId'      => 'Этап'
		];
	}
	
	public function save()
	{
		$athlete = Athlete::findOne($this->athleteId);
		
		$result = new RequestForSpecialStage();
		$result->stageId = $this->stageId;
		$result->athleteId = $this->athleteId;
		$result->motorcycleId = $this->motorcycleId;
		$result->athleteClassId = $athlete->athleteClassId;
		$result->time = HelpModel::convertTime($this->timeHuman);
		$result->fine = $this->fine;
		$result->videoLink = $this->videoLink;
		$result->date = (new \DateTime($this->dateHuman, new \DateTimeZone(HelpModel::DEFAULT_TIME_ZONE)))
			->setTime(6, 0, 0)->getTimestamp();
		if ($result->save()) {
			return true;
		}
		
		return false;
	}
}