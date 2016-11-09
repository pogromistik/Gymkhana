<?php

use yii\db\Migration;

class m161108_144420_add_user extends Migration
{
	public function safeUp()
	{
		$user = new \common\models\User();
		$user->username = 'nadia';
		$user->setPassword('oogeec4cai');
		$user->status = \common\models\User::STATUS_ACTIVE;
		$user->generateAuthKey();

		if ($user->save()) {
			$auth = \Yii::$app->authManager;
			$role = $auth->getRole('developer');
			$auth->assign($role, $user->getId());

			return $user;
		} else {
			var_dump($user->errors);
		}

	}

	public function safeDown()
	{
		return true;
	}
}
