<?php

namespace admin\models;

use common\models\City;
use common\models\RequestForSpecialStage;
use yii\base\Model;

class SpecialRequestForm extends Model
{
	public $firstName;
	public $lastName;
	public $cityId;
	public $timeHuman;
	public $fine;
	public $videoLink;
	public $date;
	public $dateHuman;
	public $time;
	public $countryId;
	public $email;
	public $athleteId;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['lastName', 'firstName',
				'timeHuman', 'videoLink', 'dateHuman', 'countryId', 'email'], 'required'],
			[['fine', 'cityId', 'countryId'], 'integer'],
			[['dateHuman', 'timeHuman', 'videoLink', 'lastName', 'firstName', 'email'], 'string'],
			['email', 'email'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'lastName'  => \Yii::t('app', 'Фамилия'),
			'firstName' => \Yii::t('app', 'Имя'),
			'timeHuman' => \Yii::t('app', 'Время без учёта штрафа'),
			'fine'      => \Yii::t('app', 'Штраф'),
			'videoLink' => \Yii::t('app', 'Ссылка на видео'),
			'dateHuman' => \Yii::t('app', 'Дата заезда'),
			'cityId'    => \Yii::t('app', 'Город'),
			'countryId' => \Yii::t('app', 'Страна'),
			'email'     => 'Email',
		];
	}
	
	public static $setAttr = [
		'lastName',
		'firstName',
		'cityId',
		'countryId',
		'email'
	];
	
	public static function set(RequestForSpecialStage $request)
	{
		$data = $request->getData();
		$form = new self();
		if ($request->athleteId) {
			$form->athleteId = $request->athleteId;
		} else {
			foreach (self::$setAttr as $attr) {
				$form->$attr = $data[$attr];
			}
		}
		$form->timeHuman = $request->timeHuman;
		$form->time = $request->time;
		$form->dateHuman = $request->dateHuman;
		$form->date = $request->date;
		$form->videoLink = $request->videoLink;
		$form->fine = $request->fine;
		
		return $form;
	}
	
	public function getCity()
	{
		return City::findOne(['id' => $this->cityId]);
	}
}