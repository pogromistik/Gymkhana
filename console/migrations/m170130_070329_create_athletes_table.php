<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sportsmans`.
 */
class m170130_070329_create_athletes_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Athletes', [
			'id'                 => $this->primaryKey(),
			'login'              => $this->integer()->unique(),
			'firstName'          => $this->string()->notNull(),
			'lastName'           => $this->string()->notNull(),
			'phone'              => $this->string(),
			'email'              => $this->string(),
			'cityId'             => $this->integer()->notNull(),
			'athleteClassId'     => $this->integer(),
			'number'             => $this->integer(),
			'authKey'            => $this->string(32),
			'passwordHash'       => $this->string(),
			'passwordResetToken' => $this->string()->unique(),
			'status'             => $this->smallInteger()->notNull()->defaultValue(1),
			'createdAt'          => $this->integer()->notNull(),
			'updatedAt'          => $this->integer()->notNull(),
			'hasAccount'         => $this->integer()->notNull()->defaultValue(0),
			'lastActivityDate'   => $this->integer()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Athletes');
	}
}
