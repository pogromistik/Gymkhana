<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "athletes".
 *
 * @property integer       $id
 * @property integer       $login
 * @property string        $firstName
 * @property string        $lastName
 * @property string        $phone
 * @property string        $email
 * @property integer       $cityId
 * @property integer       $athleteClassId
 * @property integer       $number
 * @property string        $authKey
 * @property string        $passwordHash
 * @property string        $passwordResetToken
 * @property integer       $status
 * @property integer       $createdAt
 * @property integer       $updatedAt
 * @property integer       $hasAccount
 * @property integer       $lastActivityDate
 * @property Motorcycle[]  $motorcycles
 * @property AthletesClass $athleteClass
 * @property City          $city
 */
class Athlete extends ActiveRecord implements IdentityInterface
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
	
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}
	
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
	}
	
	public function getId()
	{
		return $this->getPrimaryKey();
	}
	
	public function getAuthKey()
	{
		return $this->authKey;
	}
	
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}
	
	public static function findByLogin($login)
	{
		return static::findOne(['login' => $login]);
	}
	
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->passwordHash);
	}
	
	public function setPassword($password)
	{
		$this->passwordHash = Yii::$app->security->generatePasswordHash($password);
	}
	
	public function generateAuthKey()
	{
		$this->authKey = Yii::$app->security->generateRandomString();
	}
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Athletes';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['firstName', 'lastName', 'cityId', 'createdAt', 'updatedAt'], 'required'],
			[['login', 'cityId', 'athleteClassId', 'number', 'status', 'createdAt', 'updatedAt', 'hasAccount', 'lastActivityDate'], 'integer'],
			[['firstName', 'lastName', 'phone', 'email', 'passwordHash', 'passwordResetToken'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['number'], 'unique'],
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
			'login'              => 'Логин',
			'firstName'          => 'Имя',
			'lastName'           => 'Фамилия',
			'phone'              => 'Телефон',
			'email'              => 'Почта',
			'cityId'             => 'Город',
			'athleteClassId'     => 'Класс',
			'number'             => 'Номер',
			'authKey'            => 'Auth Key',
			'passwordHash'       => 'Password Hash',
			'passwordResetToken' => 'Password Reset Token',
			'status'             => 'Статус',
			'createdAt'          => 'Создан',
			'updatedAt'          => 'Обновлен',
			'hasAccount'         => 'Аккаунт создан?',
			'lastActivityDate'   => 'Дата последней активности',
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->createdAt = time();
		}
		$this->updatedAt = time();
		$this->firstName = HelpModel::mb_ucfirst(trim($this->firstName));
		$this->lastName = HelpModel::mb_ucfirst(trim($this->lastName));
		
		return parent::beforeValidate();
	}
	
	public function getMotorcycles()
	{
		return $this->hasMany(Motorcycle::className(), ['athleteId' => 'id']);
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public function getCity()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
	
	public static function getActiveAthletes()
	{
		$query = Athlete::find();
		$query->from([self::tableName(), Motorcycle::tableName()]);
		$query->select('"Athletes".*');
		$query->andWhere(new Expression('"Athletes"."id" = "Motorcycles"."athleteId"'));
		return $query->all();
	}
	
	public function getFullName()
	{
		return $this->lastName . ' ' . $this->firstName;
	}
}
