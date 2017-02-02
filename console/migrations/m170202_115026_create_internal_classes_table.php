<?php

use yii\db\Migration;

/**
 * Handles the creation of table `internal_classes`.
 */
class m170202_115026_create_internal_classes_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('internal_classes', [
			'id'             => $this->primaryKey(),
			'title'          => $this->string()->notNull(),
			'description'    => $this->text(),
			'championshipId' => $this->integer()->notNull(),
			'status'         => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('internal_classes');
	}
}
