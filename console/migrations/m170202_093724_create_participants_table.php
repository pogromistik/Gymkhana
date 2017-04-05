<?php

use yii\db\Migration;

/**
 * Handles the creation of table `participants`.
 */
class m170202_093724_create_participants_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Participants', [
			'id'              => $this->primaryKey(),
			'championshipId'  => $this->integer()->notNull(),
			'stageId'         => $this->integer()->notNull(),
			'athleteId'       => $this->integer()->notNull(),
			'motorcycleId'    => $this->integer()->notNull(),
			'internalClassId' => $this->integer(),
			'athleteClassId'  => $this->integer(),
			'bestTime'        => $this->integer(),
			'place'           => $this->integer(),
			'number'          => $this->integer(),
			'sort'            => $this->integer(),
			'dateAdded'       => $this->integer()->notNull(),
			'status'          => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Participants');
	}
}
