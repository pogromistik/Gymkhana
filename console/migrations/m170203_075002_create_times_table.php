<?php

use yii\db\Migration;

/**
 * Handles the creation of table `times`.
 */
class m170203_075002_create_times_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('times', [
			'id'            => $this->primaryKey(),
			'participantId' => $this->integer()->notNull(),
			'stageId'       => $this->integer()->notNull(),
			'time'          => $this->integer()->notNull(),
			'fine'          => $this->integer()->notNull()->defaultValue(0),
			'resultTime'    => $this->integer()->notNull(),
			'attemptNumber' => $this->integer()->notNull(),
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('times');
	}
}
