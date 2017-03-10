<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
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
 *
 * @property Motorcycle[]  $motorcycles
 * @property AthletesClass $athleteClass
 * @property City          $city
 * @property Region        $region
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
			[['firstName', 'lastName', 'cityId', 'createdAt', 'updatedAt', 'regionId'], 'required'],
			[['login', 'cityId', 'athleteClassId', 'regionId', 'number', 'status', 'createdAt', 'updatedAt', 'hasAccount', 'lastActivityDate'], 'integer'],
			[['firstName', 'lastName', 'phone', 'email', 'passwordHash', 'passwordResetToken', 'photo'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['login'], 'unique'],
			[['email'], 'unique'],
			[['passwordResetToken'], 'unique'],
			['number', 'validateNumber'],
			['number', 'integer', 'min' => 1],
			['number', 'integer', 'max' => 9999],
			['photoFile', 'file', 'extensions' => 'png, jpg', 'maxFiles' => 1, 'maxSize' => 102400,
			                      'tooBig'     => 'Размер файла не должен превышать 100KB']
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
			'photoFile'          => 'Фотография'
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
		$this->regionId = $this->city->regionId;
		
		return parent::beforeValidate();
	}
	
	public function beforeSave($insert)
	{
		$file = UploadedFile::getInstance($this, 'photoFile');
		if ($file && $file->size <= 102400) {
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
		if (array_key_exists('athleteClassId', $changedAttributes) && $changedAttributes['athleteClassId']) {
			$old = $changedAttributes['athleteClassId'];
			$new = $this->athleteClassId;
			$history = ClassHistory::find()->where(['athleteId' => $this->id])
				->andWhere(['oldClassId' => $old, 'newClassId' => $new])
				->orderBy(['date' => SORT_DESC])->one();
			if (!$history) {
				ClassHistory::create($this->id, null, $old, $new, 'Установлено админом');
			}
			$oldClass = AthletesClass::findOne($old);
			$text = 'Ваш класс изменен с ' . $oldClass->title . ' на ' . $this->athleteClass->title . '. ';
			if ($history && (mb_strlen($history->event) <= (253 - mb_strlen($text)))) {
				$text .= $history->event . '(' . $history->event . ')';
			}
			Notice::add($this->id, $text);
		}
		if (isset($changedAttributes['hasAccount']) && $this->hasAccount == 1) {
			Notice::add($this->id, 'Добро пожаловать! ЛК предоставляет много крутых вещей, подробнее - по ссылке');
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function getMotorcycles()
	{
		return $this->hasMany(Motorcycle::className(), ['athleteId' => 'id'])->orderBy(['status' => SORT_DESC, 'dateAdded' => SORT_DESC]);
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
}
