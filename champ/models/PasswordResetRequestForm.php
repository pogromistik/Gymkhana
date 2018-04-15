<?php
namespace champ\models;

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
			['login', 'required', 'message' => \Yii::t('app', 'Поле не может быть пустым')],
			['login', 'validateLogin'],
		];
	}
	
	public function validateLogin($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$athlete = Athlete::findOne(['email' => $this->login]);
			if (!$athlete) {
				$login = preg_replace('~\D+~','',$this->login);
				if($login == $this->login) {
					$athlete = Athlete::findOne(['login' => $this->login]);
				}
			}
			if (!$athlete) {
				$this->addError($attribute, \Yii::t('app', 'Пользователь не найден'));
			}
		}
	}
}