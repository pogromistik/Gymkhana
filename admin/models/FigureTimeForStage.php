<?php
/**
 * Created by PhpStorm.
 * User: Nadia
 * Date: 18.02.2016
 * Time: 11:59
 */

namespace admin\models;

use common\models\Participant;
use yii\base\Model;

/**
 * Signup form
 */
class FigureTimeForStage extends Model
{
	public $figureId;
	public $participantId;
	public $stageId;
	public $motorcycleId;
	public $timeForHuman;
	public $fine;
	public $date;
	public $percent;
	public $newClassId;
	public $resultTime;
	public $newClassTitle;
	public $newClassForParticipant;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['figureId', 'participantId', 'date', 'timeForHuman', 'stageId'], 'required'],
			[['percent', 'resultTime'], 'number'],
			[['newClassId', 'fine', 'newClassForParticipant', 'motorcycleId'], 'integer'],
			['newClassTitle', 'string']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'figureId'      => 'Фигура',
			'participantId' => 'Участник',
			'date'          => 'Дата заезда',
			'fine'          => 'Штраф',
			'timeForHuman'  => 'Время заезда',
			'motorcycleId'  => 'Мотоцикл',
			'stageId'       => 'Этап'
		];
	}
	
	public function beforeValidate()
	{
		if (!$this->motorcycleId) {
			$this->motorcycleId = Participant::findOne($this->participantId)->motorcycleId;
		}
		return parent::beforeValidate();
	}
}