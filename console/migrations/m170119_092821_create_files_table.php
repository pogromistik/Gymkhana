<?php

use yii\db\Migration;

/**
 * Handles the creation for table `files`.
 */
class m170119_092821_create_files_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Files', [
			'id'            => $this->primaryKey(),
			'originalTitle' => $this->string()->notNull(),
			'title'         => $this->string()->notNull(),
			'folder'        => $this->string()->notNull(),
			'dateAdded'     => $this->integer()->notNull(),
			'type'          => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Files');
	}
}
