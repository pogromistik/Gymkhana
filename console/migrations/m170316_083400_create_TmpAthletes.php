<?php

use console\components\PGMigration;

class m170316_083400_create_TmpAthletes extends PGMigration
{
	public function safeUp()
	{
		$this->createTable('TmpAthletes', [
			'id'          => $this->primaryKey(),
			'athleteId'   => $this->integer(),
			'firstName'   => $this->string()->notNull(),
			'lastName'    => $this->string()->notNull(),
			'phone'       => $this->string(),
			'email'       => $this->string()->notNull(),
			'countryId'   => $this->integer()->notNull(),
			'cityId'      => $this->integer(),
			'city'        => $this->string()->notNull(),
			'motorcycles' => $this->jsonb()->notNull(),
			'status'      => $this->integer()->notNull()->defaultValue(0),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull()
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('TmpAthletes');
	}
}
