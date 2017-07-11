<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer                $id
 * @property string                 $language
 * @property string                 $translation
 *
 * @property TranslateMessageSource $id0
 */
class TranslateMessage extends \yii\db\ActiveRecord
{
	const LANGUAGE_EN = 'en-US';
	
	public static $languagesTitle = [
		self::LANGUAGE_EN => 'Английский'
	];
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TranslateMessage';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'language'], 'required'],
			[['id'], 'integer'],
			[['translation'], 'string'],
			[['language'], 'string', 'max' => 16],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => TranslateMessageSource::className(), 'targetAttribute' => ['id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'language'    => 'Язык',
			'translation' => 'Перевод',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getId0()
	{
		return $this->hasOne(TranslateMessageSource::className(), ['id' => 'id']);
	}
}
