<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Comments`.
 */
class m181001_175003_create_Comments_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('Comments', [
			'id'          => $this->primaryKey(),
			'modelClass'  => $this->string()->notNull(),
			'modelId'     => $this->integer()->notNull(),
			'text'        => $this->text()->notNull(),
			'athleteId'   => $this->integer()->notNull(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull()
		]);
		
		$this->createIndex('Comments_modelClass', 'Comments', 'modelClass');
		$this->createIndex('Comments_modelId', 'Comments', 'modelId');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('Comments');
	}
}
