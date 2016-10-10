<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "years".
 *
 * @property integer $id
 * @property integer  $year
 * @property integer $status
 */
class Year extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	public static $statusesTitle = [
		self::STATUS_ACTIVE   => 'Активен',
		self::STATUS_INACTIVE => 'Заблокирован'
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'years';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['year'], 'required'],
			[['status', 'year'], 'integer']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'     => 'ID',
			'year'   => 'Год',
			'status' => 'Статус',
		];
	}
}
