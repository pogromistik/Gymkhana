<?php

namespace admin\models;

use common\models\AthletesClass;
use yii\base\Model;

class MergeMotorcyclesForm extends Model
{
	public $athleteId;
	public $firstMotorcycles;
	public $secondMotorcycles;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			
			[['athleteId'], 'required'],
			[['athleteId'], 'integer'],
			[['firstMotorcycles', 'secondMotorcycles'], 'integer']
		];
	}
	
	public function attributeLabels()
	{
		return [
			'athleteId'         => 'Спортсмен',
			'firstMotorcycles'  => 'Первый мотоцикл',
			'secondMotorcycles' => 'Второй мотоцикл'
		];
	}
}