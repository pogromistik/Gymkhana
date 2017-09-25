<?php

use yii\db\Migration;

class m170925_042959_add_work_table extends Migration
{
	public function safeUp()
	{
		$this->createTable('Work', [
			'id'        => $this->primaryKey(),
			'status'    => $this->integer()->notNull()->defaultValue(0),
			'text'      => $this->text()->notNull(),
			'dateStart' => $this->integer(),
			'time'      => $this->integer()->notNull()->defaultValue(5)
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('Work');
	}
}
