<?php

use yii\db\Migration;

/**
 * Handles the creation of table `TmpParticipants`.
 */
class m170215_134500_create_TmpParticipants_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('TmpParticipants', [
			'id'              => $this->primaryKey(),
			'championshipId'  => $this->integer()->notNull(),
			'stageId'         => $this->integer()->notNull(),
			'firstName'       => $this->string()->notNull(),
			'lastName'        => $this->string()->notNull(),
			'city'            => $this->string()->notNull(),
			'cityId'          => $this->integer(),
			'motorcycleMark'  => $this->string()->notNull(),
			'motorcycleModel' => $this->string()->notNull(),
			'phone'           => $this->string(),
			'number'          => $this->integer(),
			'dateAdded'       => $this->integer()->notNull(),
			'dateUpdated'     => $this->integer()->notNull(),
			'status'          => $this->integer()->notNull()->defaultValue(1),
			'athleteId'       => $this->integer()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('TmpParticipants');
	}
}
