<?php

namespace common\models;

use common\components\BaseActiveRecord;
use common\helpers\UserHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

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
 * @property integer       $regionId
 * @property integer       $photo
 * @property integer       $countryId
 * @property integer       $creatorUserId
 *
 * @property Motorcycle[]  $motorcycles
 * @property Motorcycle[]  $activeMotorcycles
 * @property AthletesClass $athleteClass
 * @property City          $city
 * @property Region        $region
 * @property Country       $country
 */
class Athlete extends BaseActiveRecord implements IdentityInterface
{
	protected static $enableLogging = true;
	protected static $ignoredAttributes = [
		'authKey',
		'passwordHash',
		'passwordResetToken',
		'updatedAt',
		'createdAt',
		'lastActivityDate',
		'photo'
	];
	
	public $photoFile;
	
	const STATUS_BLOCKED = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_WAIT = 2;
	const STATUS_DELETE = 3;
	const TIMEOUT = 3600;
	
	public static $classesCss = [
		'A'  => 'red',
		'B'  => 'blue',
		'C1' => 'green',
		'C2' => 'green',
		'C3' => 'green',
		'C4' => 'green',
		'C5' => 'green',
		'С1' => 'green',
		'С2' => 'green',
		'С3' => 'green',
		'С4' => 'green',
		'С5' => 'green',
		'D1' => 'yellow',
		'D2' => 'yellow',
		'D3' => 'yellow',
		'D4' => 'yellow',
		'D5' => 'yellow',
		'N'  => 'white'
	];
	
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
		$notEmail = preg_replace('~\D+~', '', $login);
		if ($notEmail === $login) {
			$athlete = static::findOne(['login' => $login, 'status' => self::STATUS_ACTIVE]);
		} else {
			$athlete = static::findOne(['upper("email")' => mb_strtoupper($login), 'status' => self::STATUS_ACTIVE]);
		}
		
		return $athlete;
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
			[['firstName', 'lastName', 'cityId', 'createdAt', 'updatedAt', 'regionId', 'countryId'], 'required'],
			[['login', 'cityId', 'athleteClassId', 'regionId', 'number', 'status',
				'createdAt', 'updatedAt', 'hasAccount', 'lastActivityDate', 'countryId',
				'creatorUserId'], 'integer'],
			[['firstName', 'lastName', 'phone', 'email', 'passwordHash', 'passwordResetToken', 'photo'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['passwordResetToken'], 'unique'],
			['number', 'validateNumber'],
			['number', 'integer', 'min' => 1],
			['number', 'integer', 'max' => 999],
			['photoFile', 'file', 'extensions' => 'png, jpg', 'maxFiles' => 1, 'maxSize' => 307200,
			                      'tooBig'     => 'Размер файла не должен превышать 300KB']
		];
	}
	
	public function validateNumber($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$regionId = $this->city->regionId;
			$query = new Query();
			$query->from([self::tableName(), City::tableName(), Region::tableName()]);
			$query->where(['Regions."id"' => $regionId]);
			$query->andWhere(new Expression('"Athletes"."cityId" = "Cities"."id"'));
			$query->andWhere(new Expression('"Cities"."regionId" = "Regions"."id"'));
			$query->andWhere(['Athletes."number"' => $this->number]);
			$query->andWhere(['not', ['Athletes."id"' => $this->id]]);
			if ($query->one()) {
				$this->addError($attribute, 'В вашей области уже есть человек с таким номером.');
			} else {
				$query = new Query();
				$query->from(['a' => Stage::tableName(), 'b' => Championship::tableName()]);
				$query->select('a.id');
				$query->where(['not', ['a.status' => Stage::STATUS_PAST]]);
				$query->andWhere(['not', ['b.regionId' => null]]);
				$query->andWhere(new Expression('"a"."championshipId" = "b"."id"'));
				$stageIds = $query->column();
				if ($stageIds) {
					/** @var Participant $busy */
					$busy = Participant::find()->where(['stageId' => $stageIds])->andWhere(['not', ['athleteId' => $this->id]])
						->andWhere(['number' => $this->number])->one();
					if (!$busy) {
						$busy = TmpParticipant::find()->where(['stageId' => $stageIds])
							->andWhere(['number' => $this->number])->andWhere(['status' => TmpParticipant::STATUS_NEW])
							->one();
					}
					if ($busy) {
						$this->addError($attribute, 'Вы не можете занять этот номер, пока не закончится этап 
						"' . $busy->stage->title . '" 
						чемпионата "' . $busy->championship->title . '"');
					} else {
						
					}
				}
			}
		}
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
			'regionId'           => 'Регион',
			'photo'              => 'Фотография',
			'photoFile'          => 'Фотография',
			'countryId'          => 'Страна'
		];
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->createdAt = time();
			if (!$this->athleteClassId) {
				$class = AthletesClass::getStartClass();
				if ($class) {
					$this->athleteClassId = $class->id;
				}
			}
			$this->creatorUserId = UserHelper::getUserId();
		}
		$this->email = trim(mb_strtolower($this->email));
		$this->updatedAt = time();
		$this->firstName = HelpModel::mb_ucfirst(trim($this->firstName));
		$this->lastName = HelpModel::mb_ucfirst(trim($this->lastName));
		$this->regionId = $this->city->regionId;
		if ($this->phone) {
			$this->phone = preg_replace('~\D+~', '', $this->phone);
		}
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'photoFile');
		if ($file && $file->size <= 307200) {
			if ($this->photo) {
				$filePath = Yii::getAlias('@files') . $this->photo;
				if (file_exists($filePath)) {
					unlink($filePath);
				}
			}
			$dir = \Yii::getAlias('@files') . '/' . 'athletes';
			if (!file_exists($dir)) {
				mkdir($dir);
			}
			$title = uniqid() . '.' . $file->extension;
			$folder = $dir . '/' . $title;
			if ($file->saveAs($folder)) {
				$this->photo = '/athletes/' . $title;
			}
		}
		
		return parent::beforeSave($insert);
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		if (array_key_exists('athleteClassId', $changedAttributes) && $changedAttributes['athleteClassId']
			&& $changedAttributes['athleteClassId'] != $this->athleteClassId
		) {
			$old = $changedAttributes['athleteClassId'];
			$new = $this->athleteClassId;
			$history = ClassHistory::find()->where(['athleteId' => $this->id])
				->andWhere(['oldClassId' => $old, 'newClassId' => $new])
				->orderBy(['date' => SORT_DESC])->one();
			if (!$history) {
				ClassHistory::create($this->id, null, $old, $new, 'Установлено админом');
			}
			if ($this->hasAccount) {
				$oldClass = AthletesClass::findOne($old);
				$newClass = AthletesClass::findOne($new);
				$text = 'Ваш класс изменен с ' . $oldClass->title . ' на ' . $newClass->title . '. ';
				if ($history && (mb_strlen($history->event) <= (253 - mb_strlen($text)))) {
					$text .= $history->event . ' (' . $history->event . ')';
				}
				Notice::add($this->id, $text);
			}
		}
		if (array_key_exists('hasAccount', $changedAttributes) && $this->hasAccount == 1 && $changedAttributes['hasAccount'] != 1) {
			$link = Url::to(['/profile/help']);
			Notice::add($this->id, 'Добро пожаловать! ЛК предоставляет много крутых вещей. Если вам требуется помощь - нажмите на ссылку ниже.', $link);
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function getMotorcycles()
	{
		return $this->hasMany(Motorcycle::className(), ['athleteId' => 'id'])->orderBy(['status' => SORT_DESC, 'dateAdded' => SORT_DESC]);
	}
	
	public function getActiveMotorcycles()
	{
		return $this->hasMany(Motorcycle::className(), ['athleteId' => 'id'])->andOnCondition(['status' => Motorcycle::STATUS_ACTIVE])->orderBy(['dateAdded' => SORT_DESC]);
	}
	
	public function getAthleteClass()
	{
		return $this->hasOne(AthletesClass::className(), ['id' => 'athleteClassId']);
	}
	
	public function getCity()
	{
		return $this->hasOne(City::className(), ['id' => 'cityId']);
	}
	
	public function getRegion()
	{
		return $this->hasOne(Region::className(), ['id' => 'regionId']);
	}
	
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'countryId']);
	}
	
	public static function getActiveAthletes($withoutId = null)
	{
		$query = Athlete::find();
		$query->from([self::tableName(), Motorcycle::tableName()]);
		$query->select('"Athletes".*');
		$query->andWhere(new Expression('"Athletes"."id" = "Motorcycles"."athleteId"'));
		if ($withoutId) {
			$query->andWhere(['not', ['"Athletes"."id"' => $withoutId]]);
		}
		$query->orderBy(['lastName' => SORT_ASC]);
		
		return $query->all();
	}
	
	public function getFullName()
	{
		return $this->lastName . ' ' . $this->firstName;
	}
	
	public function createCabinet()
	{
		$password = $this->generatePassword();
		$this->login = $this->id + 6000;
		$this->generateAuthKey();
		$this->setPassword($password);
		$this->hasAccount = 1;
		$this->status = self::STATUS_ACTIVE;
		if (!$this->save()) {
			return false;
		}
		
		if (YII_ENV != 'dev') {
			\Yii::$app->mailer->compose('new-account', ['athlete' => $this, 'password' => $password])
				->setTo($this->email)
				->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
				->setSubject('gymkhana-cup: регистрация на сайте')
				->send();
		}
		
		return true;
	}
	
	public function generatePassword()
	{
		$arr = ['a', 'b', 'c', 'd', 'e', 'f',
			'g', 'h', 'i', 'j', 'k', 'l',
			'm', 'n', 'o', 'p', 'q', 'r',
			's', 't', 'u', 'v', 'w', 'x',
			'y', 'z', '1', '2', '3', '4',
			'5', '6', '7', '8', '9', '0'
		];
		
		// Генерируем пароль
		$pass = "";
		for ($i = 0; $i < 8; $i++) {
			// Вычисляем случайный индекс массива
			$index = rand(0, count($arr) - 1);
			$pass .= $arr[$index];
		}
		
		return $pass;
	}
	
	public function deleteCabinet()
	{
		$this->login = null;
		$this->authKey = null;
		$this->passwordHash = null;
		$this->hasAccount = 0;
		$this->status = self::STATUS_DELETE;
		if (!$this->save()) {
			return false;
		}
		
		return true;
	}
	
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}
		
		return static::findOne([
			'passwordResetToken' => $token,
			'status'             => self::STATUS_ACTIVE,
		]);
	}
	
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
	
	public function removePasswordResetToken()
	{
		$this->passwordResetToken = null;
	}
	
	public function generatePasswordResetToken()
	{
		$this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
	}
	
	public function resetPassword()
	{
		if (!Athlete::isPasswordResetTokenValid($this->passwordResetToken)) {
			$this->generatePasswordResetToken();
		}
		if ($this->save()) {
			$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/new-password', 'token' => $this->passwordResetToken]);
			
			if (YII_ENV != 'dev') {
				\Yii::$app->mailer->compose('reset-password', ['resetLink' => $resetLink])
					->setTo($this->email)
					->setFrom(['support@gymkhana-cup.ru' => 'GymkhanaCup'])
					->setSubject('gymkhana-cup: восстановление пароля')
					->send();
			}
			
			return true;
		}
		
		return false;
	}
}
