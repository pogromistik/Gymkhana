<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
	public function up()
	{
		$this->createTable('Users', [
			'id'                 => $this->primaryKey(),
			'username'           => $this->string()->notNull()->unique(),
			'authKey'            => $this->string(32)->notNull(),
			'passwordHash'       => $this->string()->notNull(),
			'passwordResetToken' => $this->string()->unique(),
			'status'             => $this->smallInteger()->notNull()->defaultValue(10),
			'createdAt'          => $this->integer()->notNull(),
			'updatedAt'          => $this->integer()->notNull(),
		]);
	}

	public function down()
	{
		$this->dropTable('Users');
	}
}
