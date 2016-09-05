<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $authKey
 * @property string  $passwordHash
 * @property string  $passwordResetToken
 * @property integer $status
 * @property integer $createdAt
 * @property integer $updatedAt
 */
class User extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'users';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'authKey', 'passwordHash', 'createdAt', 'updatedAt'], 'required'],
			[['status', 'createdAt', 'updatedAt'], 'integer'],
			[['username', 'passwordHash', 'passwordResetToken'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['username'], 'unique'],
			[['passwordResetToken'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'username'           => 'Username',
			'authKey'            => 'Auth Key',
			'passwordHash'       => 'Password Hash',
			'passwordResetToken' => 'Password Reset Token',
			'status'             => 'Status',
			'createdAt'          => 'Created At',
			'updatedAt'          => 'Updated At',
		];
	}
}
