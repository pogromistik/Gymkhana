<?php

use yii\db\Migration;

/**
 * Handles the creation of table `SpecialChamps`.
 */
class m171012_073301_create_SpecialChamps_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('SpecialChamps', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string()->notNull(),
			'description' => $this->text(),
			'yearId'      => $this->integer()->notNull(),
			'status'      => $this->integer()->notNull()->defaultValue(1),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('SpecialChamps');
	}
}
