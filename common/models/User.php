<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class User extends ActiveRecord implements IdentityInterface
{
	const STATUS_BLOCKED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_WAIT = 2;
	const STATUS_DELETE = 3;
	const TIMEOUT = 3600;

	public static $statusesTitle =
		[
			self::STATUS_BLOCKED => 'Заблокирован',
			self::STATUS_ACTIVE  => 'Активен',
			self::STATUS_WAIT    => 'Ожидает подтверждения',
			self::STATUS_DELETE  => 'Удалён',
		];
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
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 *
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username]);
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 *
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->passwordHash);
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	 * @return string
	 */
	public function getStatusName()
	{
		$statuses = self::getStatusesArray();

		return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
	}

	/**
	 * @return array
	 */
	public static function getStatusesArray()
	{
		return [
			self::STATUS_BLOCKED => 'Заблокирован',
			self::STATUS_ACTIVE  => 'Активен',
			self::STATUS_WAIT    => 'Ожидает подтверждения',
			self::STATUS_DELETE  => 'Удалён',
		];
	}

	/**
	 * @param $id
	 *
	 * @return null|static
	 * @throws \Exception
	 */
	public function deleteUser($id)
	{
		$user = $this->findOne($id);
		$user->status = User::STATUS_DELETE;
		$user->update();

		return $user;
	}

	/**
	 * @param $id
	 *
	 * @return null|static
	 * @throws \Exception
	 */
	public function recoveryUser($id)
	{
		$user = $this->findOne($id);
		$user->status = User::STATUS_ACTIVE;
		$user->update();

		return $user;
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->authKey = Yii::$app->security->generateRandomString();
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				$this->generateAuthKey();
			}

			return true;
		}

		return false;
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

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 *
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status'               => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 *
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int)end($parts);

		return $timestamp + $expire >= time();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	/**
	 * @param string $email_confirm_token
	 *
	 * @return static|null
	 */
	public static function findByEmailConfirmToken($email_confirm_token)
	{
		return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
	}

	/**
	 * Generates email confirmation token
	 */
	public function generateEmailConfirmToken()
	{
		$this->email_confirm_token = Yii::$app->security->generateRandomString();
	}

	/**
	 * Removes email confirmation token
	 */
	public function removeEmailConfirmToken()
	{
		$this->email_confirm_token = null;
	}
}
