<?php
/**
 * Created by PhpStorm.
 * User: lyadetskaya.ns
 * Date: 16.07.2015
 * Time: 9:47
 */

namespace admin\models;

use common\models\Athlete;
use common\models\User;
use yii\base\Model;
use Yii;


class PasswordForm extends Model
{
	public $pass;
	public $pass_repeat;
	public $athleteId;
	
	public function rules()
	{
		return [
			['athleteId', 'required'],
			[['pass', 'pass_repeat'], 'required', 'message' => 'Поле не может быть пустым'],
			['pass', 'string', 'min' => 8, 'message' => 'Пароль не может быть менее 8 символов'],
			['pass_repeat', 'compare', 'compareAttribute' => 'pass', 'message' => 'Пароли не совпадают'],
		];
	}
	
	public function savePassw()
	{
		$user = Athlete::findOne($this->athleteId);
		$user->setPassword($this->pass);
		$user->save();
		
		return $this->pass;
	}
	
	public function saveForAdmins()
	{
		$user = User::findOne(\Yii::$app->user->identity->id);
		$user->password = $this->pass;
		$user->save();
		
		return true;
	}
	
	public function checkPassword()
	{
		$array = str_split($this->pass);
		$unique = [];
		foreach ($array as $item) {
			if (array_search($item, $unique) === false) {
				$unique[] = $item;
			}
		}
		
		if (count($unique) < 5) {
			return 'Должно быть минимум 5 уникальных символов';
		}
		
		if(!preg_match("/([a-z]+)/", $this->pass))
		{
			return 'Пароль должен содержать маленькие буквы';
		}
		
		if(!preg_match("/([A-Z]+)/", $this->pass))
		{
			return 'Пароль должен содержать большие буквы';
		}
		
		return null;
	}
	
	public static function staticCheckPassword($pass)
	{
		$array = str_split($pass);
		$unique = [];
		foreach ($array as $item) {
			if (array_search($item, $unique) === false) {
				$unique[] = $item;
			}
		}
		
		if (count($unique) < 5) {
			return 'Должно быть минимум 5 уникальных символов';
		}
		
		if(!preg_match("/([a-z]+)/", $pass))
		{
			return 'Пароль должен содержать маленькие буквы';
		}
		
		if(!preg_match("/([A-Z]+)/", $pass))
		{
			return 'Пароль должен содержать большие буквы';
		}
		
		return null;
	}
}

?>