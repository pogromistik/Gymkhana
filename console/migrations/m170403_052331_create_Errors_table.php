<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Errors`.
 */
class m170403_052331_create_Errors_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Errors', [
			'id'          => $this->primaryKey(),
			'type'        => $this->integer()->notNull()->defaultValue(1),
			'text'        => $this->text(),
			'status'      => $this->integer()->notNull()->defaultValue(0),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Errors');
	}
}
