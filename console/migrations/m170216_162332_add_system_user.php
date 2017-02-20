<?php

use yii\db\Migration;

class m170216_162332_add_system_user extends Migration
{
	public function safeUp()
	{
		$this->insert(\common\models\User::tableName(), [
			'id'               => -1,
			'username'         => 'system',
			'email'            => 'system@852ex.ru',
			'password_hash'    => '456',
			'auth_key'         => '123',
			'confirmed_at'     => time(),
			'registration_ip'  => '10.0.0.1',
			'created_at'       => time(),
			'updated_at'       => time(),
			'flags'            => 0,
		]);
	}
	
	public function safeDown()
	{
		return true;
	}
}
