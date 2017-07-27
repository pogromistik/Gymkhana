<?php
/**
 * Created by PhpStorm.
 * User: lyadetskaya.ns
 * Date: 16.07.2015
 * Time: 9:47
 */

namespace admin\models;

use common\models\Athlete;
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
			['pass', 'string', 'min' => 6, 'message' => 'Пароль не может быть менее 6 символов'],
			['pass_repeat', 'compare', 'compareAttribute'=>'pass', 'message' => 'Пароли не совпадают'],
		];
	}
	
	public function savePassw(){
		$user = Athlete::findOne($this->athleteId);
		$user->setPassword($this->pass);
		$user->save();
		
		return $this->pass;
	}
}
?>