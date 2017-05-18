<?php
/**
 * Created by PhpStorm.
 * User: Nadia
 * Date: 18.02.2016
 * Time: 11:59
 */

namespace admin\models;

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
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['figureId', 'participantId', 'date', 'timeForHuman', 'motorcycleId', 'stageId'], 'required'],
			[['percent', 'resultTime'], 'number'],
			[['newClassId', 'fine'], 'integer'],
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
}