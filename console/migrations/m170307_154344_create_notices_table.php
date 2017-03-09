<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notices`.
 */
class m170307_154344_create_notices_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Notices', [
			'id'        => $this->primaryKey(),
			'athleteId' => $this->integer()->notNull(),
			'text'      => $this->string()->notNull(),
			'link'      => $this->string(),
			'status'    => $this->integer()->notNull()->defaultValue(1),
			'dateAdded' => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Notices');
	}
}
