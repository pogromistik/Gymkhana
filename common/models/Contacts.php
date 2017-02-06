<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property string  $phone
 * @property string  $email
 * @property string  $addr
 * @property string  $time
 * @property string  $card
 * @property string  $cardInfo
 */
class Contacts extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Contacts';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'addr', 'time', 'smallInfo'], 'required'],
			[['time', 'smallInfo'], 'string'],
			[['phone', 'email', 'addr', 'card', 'cardInfo'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'        => 'ID',
			'phone'     => 'Телефон',
			'email'     => 'Email',
			'addr'      => 'Адрес',
			'time'      => 'Время',
			'card'      => 'Банковская карта',
			'cardInfo'  => 'Информация по карте (напр. ФИО)',
			'smallInfo' => 'Информация для подвала'
		];
	}
}
