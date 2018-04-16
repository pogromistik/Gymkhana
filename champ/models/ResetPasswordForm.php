<?php
namespace champ\models;

use common\models\Athlete;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
	public $password;
	public $passwordRepeat;
	/**
	 * @var Athlete
	 */
	private $_user;
	/**
	 * Creates a form model given a token.
	 *
	 * @param  string                          $token
	 * @param  array                           $config name-value pairs that will be used to initialize the object properties
	 * @throws \yii\base\InvalidParamException if token is empty or not valid
	 */
	public function __construct($token, $config = [])
	{
		if (empty($token) || !is_string($token)) {
			throw new InvalidParamException('Password reset token cannot be blank.');
		}
		$this->_user = Athlete::findByPasswordResetToken($token);
		if (!$this->_user) {
			throw new InvalidParamException('Wrong password reset token.');
		}
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['password', 'required', 'message' => \Yii::t('app', 'Поле не может быть пустым')],
			['password', 'string', 'min' => 6, 'message' => \Yii::t('app', 'Пароль должен состоять минимум из 6 символов')],
			['passwordRepeat', 'compare', 'compareAttribute'=>'password', 'message' => \Yii::t('app', 'Пароли не совпадают')],
		];
	}
	/**
	 * Resets password.
	 *
	 * @return boolean if password was reset.
	 */
	public function resetPassword()
	{
		$user = $this->_user;
		$user->setPassword($this->password);
		$user->removePasswordResetToken();
		if (!$user->save()) {
			return false;
		}
		Yii::$app->user->login($user);
		return true;
	}
}