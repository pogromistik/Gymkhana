<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Messages`.
 */
class m170705_055114_create_Messages_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Messages', [
			'id'        => $this->primaryKey(),
			'title'     => $this->string()->notNull(),
			'text'      => $this->text()->notNull(),
			'userId'    => $this->integer()->notNull(),
			'dateAdded' => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Messages');
	}
}
