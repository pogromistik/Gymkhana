<?php
/**
 * Created by PhpStorm.
 * User: Nadia
 * Date: 18.02.2016
 * Time: 11:59
 */

namespace champ\models;

use common\models\HelpModel;
use common\models\RequestForSpecialStage;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class SpecialStageForm extends Model
{
	public $firstName;
	public $lastName;
	public $motorcycleMark;
	public $motorcycleModel;
	public $cityId;
	public $cityTitle;
	public $timeHuman;
	public $fine;
	public $videoLink;
	public $date;
	public $dateHuman;
	public $time;
	public $stageId;
	public $countryId;
	public $email;
	public $power;
	public $cbm;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['lastName', 'firstName', 'motorcycleMark', 'motorcycleModel',
				'timeHuman', 'videoLink', 'dateHuman', 'stageId', 'countryId', 'email', 'power', 'cbm'], 'required'],
			[['fine', 'stageId', 'cityId', 'countryId', 'cbm'], 'integer'],
			[['power'], 'number'],
			[['dateHuman', 'timeHuman', 'videoLink', 'lastName', 'firstName',
				'motorcycleMark', 'motorcycleModel', 'cityTitle', 'email'], 'string'],
			['email', 'email']
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'lastName'        => 'Фамилия',
			'firstName'       => 'Имя',
			'motorcycleMark'  => 'Марка мотоцикла',
			'motorcycleModel' => 'Модель мотоцикла',
			'motorcycleId'    => 'Мотоцикл',
			'timeHuman'       => 'Время без учёта штрафа',
			'fine'            => 'Штраф',
			'videoLink'       => 'Ссылка на видео',
			'dateHuman'       => 'Дата заезда',
			'stageId'         => 'Этап',
			'cityId'          => 'Город',
			'cityTitle'       => 'Город',
			'countryId'       => 'Страна',
			'email'           => 'Email',
			'power'           => 'Мощность',
			'cbm'             => 'Объём'
		];
	}
	
	public function save()
	{
		$result = new RequestForSpecialStage();
		$result->data = json_encode(ArrayHelper::toArray($this));
		$result->stageId = $this->stageId;
		$result->time = HelpModel::convertTime($this->timeHuman);
		$result->fine = $this->fine;
		$result->videoLink = $this->videoLink;
		$result->date = (new \DateTime($this->dateHuman, new \DateTimeZone(HelpModel::DEFAULT_TIME_ZONE)))
			->setTime(6, 0, 0)->getTimestamp();
		if ($this->cityId) {
			$result->cityId = $this->cityId;
		}
		$result->countryId = $this->countryId;
		if ($result->save()) {
			return true;
		}
		
		return false;
	}
}