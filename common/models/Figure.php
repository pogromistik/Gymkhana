<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Figures".
 *
 * @property integer      $id
 * @property string       $title
 * @property string       $description
 * @property string       $file
 * @property string       $picture
 * @property integer      $bestTime
 * @property string       $bestAthlete
 * @property integer      $bestTimeInRussia
 * @property string       $bestAthleteInRussia
 *
 * @property FigureTime[] $results
 */
class Figure extends \yii\db\ActiveRecord
{
	public $bestTimeForHuman;
	public $bestTimeInRussiaForHuman;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Figures';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'bestTimeForHuman'], 'required'],
			[['description', 'bestAthlete', 'bestAthleteInRussia', 'bestTimeForHuman', 'bestTimeInRussiaForHuman'], 'string'],
			[['bestTime', 'bestTimeInRussia'], 'integer'],
			[['title', 'file', 'picture'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                       => 'ID',
			'title'                    => 'Название',
			'description'              => 'Описание',
			'file'                     => 'Файл',
			'picture'                  => 'Изображение',
			'bestTime'                 => 'Эталонное время',
			'bestTimeForHuman'         => 'Эталонное время',
			'bestAthlete'              => 'Мировой рекордсмен',
			'bestTimeInRussia'         => 'Лучшее время в России',
			'bestTimeInRussiaForHuman' => 'Лучшее время в России',
			'bestAthleteInRussia'      => 'Рекордсмен в России',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->bestTimeForHuman) {
			list($min, $secs) = explode(':', $this->bestTimeForHuman);
			$this->bestTime = ($min * 60000) + $secs * 1000;
		}
		
		if ($this->bestTimeInRussiaForHuman) {
			list($min, $secs) = explode(':', $this->bestTimeInRussiaForHuman);
			$this->bestTimeInRussia = ($min * 60000) + $secs * 1000;
		}
		
		return parent::beforeValidate();
	}
	
	public function afterFind()
	{
		parent::afterFind();
		if ($this->bestTime) {
			$min = str_pad(floor($this->bestTime / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->bestTime - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->bestTime - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->bestTimeForHuman = $min . ':' . $sec . '.' . $mls;
		}
		
		if ($this->bestTimeInRussia) {
			$min = str_pad(floor($this->bestTimeInRussia / 60000), 2, '0', STR_PAD_LEFT);
			$sec = str_pad(floor(($this->bestTimeInRussia - $min * 60000) / 1000), 2, '0', STR_PAD_LEFT);
			$mls = str_pad(($this->bestTimeInRussia - $min * 60000 - $sec * 1000) / 10, 2, '0', STR_PAD_LEFT);
			$this->bestTimeInRussiaForHuman = $min . ':' . $sec . '.' . $mls;
		}
	}
	
	public function getResults()
	{
		return $this->hasMany(FigureTime::className(), ['figureId' => 'id']);
	}
}
