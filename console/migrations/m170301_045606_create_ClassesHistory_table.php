<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ClassesHistory`.
 */
class m170301_045606_create_ClassesHistory_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('ClassesHistory', [
			'id'           => $this->primaryKey(),
			'athleteId'    => $this->integer()->notNull(),
			'motorcycleId' => $this->integer(),
			'oldClassId'   => $this->integer()->notNull(),
			'newClassId'   => $this->integer()->notNull(),
			'event'        => $this->text()->notNull(),
			'time'         => $this->integer(),
			'bestTime'     => $this->integer(),
			'date'         => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('ClassesHistory');
	}
}
