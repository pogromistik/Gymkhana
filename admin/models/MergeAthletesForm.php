<?php

namespace admin\models;

use common\models\AthletesClass;
use yii\base\Model;

class MergeAthletesForm extends Model
{
	public $firstAthleteId;
	public $secondAthleteId;
	public $firstMotorcycles;
	public $secondMotorcycles;
	/**
	 * @var AthletesClass $resultClass
	 */
	public $resultClass;
	public $number;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['firstAthleteId', 'secondAthleteId'], 'required'],
			[['firstAthleteId', 'secondAthleteId'], 'integer'],
			[['firstMotorcycles', 'secondMotorcycles'], 'safe']
		];
	}
	
	public function attributeLabels()
	{
		return [
			'firstAthleteId'    => 'Первый спортсмен',
			'secondAthleteId'   => 'Второй спортсмен',
			'firstMotorcycles'  => 'Первый мотоцикл',
			'secondMotorcycles' => 'Второй мотоцикл'
		];
	}
}