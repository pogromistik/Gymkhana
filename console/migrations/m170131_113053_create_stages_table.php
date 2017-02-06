<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stages`.
 */
class m170131_113053_create_stages_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Stages', [
			'id'                => $this->primaryKey(),
			'championshipId'    => $this->integer()->notNull(),
			'title'             => $this->string()->notNull(),
			'location'          => $this->string(),
			'cityId'            => $this->integer()->notNull(),
			'description'       => $this->text(),
			'dateAdded'         => $this->integer()->notNull(),
			'dateUpdated'       => $this->integer()->notNull(),
			'dateOfThe'         => $this->integer(),
			'startRegistration' => $this->integer(),
			'endRegistration'   => $this->integer(),
			'status'            => $this->integer()->notNull()->defaultValue(1),
			'class'             => $this->integer()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Stages');
	}
}
