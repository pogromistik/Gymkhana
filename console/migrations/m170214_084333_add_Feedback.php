<?php

use yii\db\Migration;

class m170214_084333_add_Feedback extends Migration
{
	public function safeUp()
	{
		$this->createTable('Feedback', [
			'id'          => $this->primaryKey(),
			'username'    => $this->string()->notNull(),
			'phone'       => $this->string(),
			'email'       => $this->string(),
			'text'        => $this->text()->notNull(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull(),
			'athleteId'   => $this->integer(),
			'isNew'       => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('Feedback');
	}
}
