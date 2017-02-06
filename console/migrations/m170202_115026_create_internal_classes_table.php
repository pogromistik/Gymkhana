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
		$this->createTable('InternalClasses', [
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
		$this->dropTable('InternalClasses');
	}
}
