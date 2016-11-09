<?php
/**
 * Created by PhpStorm.
 * User: Nadia
 * Date: 18.02.2016
 * Time: 11:59
 */

namespace admin\models;
use common\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $username;
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [

			[['username', 'password'], 'required'],
			['username', 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь с таким ником уже существует.'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'username' => 'Ник',
			'password' => 'Пароль',
		];
	}

	public function save()
	{
		if ($this->validate()) {
			$user = new User();
			$user->username = $this->username;
			$user->setPassword($this->password);
			$user->status = User::STATUS_ACTIVE;
			$user->generateAuthKey();

			if ($user->save()) {
				$auth = \Yii::$app->authManager;
				$role = $auth->getRole('manager');
				$auth->assign($role, $user->getId());

				return $user;
			}
		}

		return null;

	}
}