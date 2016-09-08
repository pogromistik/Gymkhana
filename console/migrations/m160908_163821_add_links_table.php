<?php

use yii\db\Migration;

class m160908_163821_add_links_table extends Migration
{
	public function up()
	{
		$this->createTable('links', [
			'id'    => $this->primaryKey(),
			'link'  => $this->string(),
			'title' => $this->string(),
			'class' => $this->string()
		]);
	}

	public function down()
	{
		$this->dropTable('links');
	}

	/*
	// Use safeUp/safeDown to run migration code within a transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
