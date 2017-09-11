<?php
namespace champ\models;

use common\components\XED;
use common\models\Athlete;
use Yii;
use yii\base\Model;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
	public $login;
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['login', 'filter', 'filter' => 'trim'],
			['login', 'required', 'message' => 'Поле не может быть пустым'],
			['login', 'validateLogin'],
		];
	}
	
	public function validateLogin($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$athlete = Athlete::findOne(['email' => XED::encrypt($this->login, Athlete::$hash)]);
			if (!$athlete) {
				$login = preg_replace('~\D+~','',$this->login);
				if($login == $this->login) {
					$athlete = Athlete::findOne(['login' => $this->login]);
				}
			}
			if (!$athlete) {
				$this->addError($attribute, 'Пользователь не найден.');
			}
		}
	}
}