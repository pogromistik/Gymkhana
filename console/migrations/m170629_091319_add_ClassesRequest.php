<?php

use yii\db\Migration;

class m170629_091319_add_ClassesRequest extends Migration
{
	public function safeUp()
	{
		$this->createTable('ClassesRequest', [
			'id'         => $this->primaryKey(),
			'dateAdded'  => $this->integer()->notNull(),
			'status'     => $this->integer()->notNull()->defaultValue(0),
			'comment'    => $this->text()->notNull(),
			'athleteId'  => $this->integer()->notNull(),
			'newClassId' => $this->integer()->notNull(),
			'feedback'   => $this->string()
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('ClassesRequest');
	}
}
