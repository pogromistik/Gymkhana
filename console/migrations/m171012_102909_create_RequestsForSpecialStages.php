<?php

use console\components\PGMigration;

class m171012_102909_create_RequestsForSpecialStages extends PGMigration
{
	public function safeUp()
	{
		$this->createTable('RequestsForSpecialStages', [
			'id'                    => $this->primaryKey(),
			'data'                  => $this->jsonb(),
			'athleteId'             => $this->integer(),
			'motorcycleId'          => $this->integer(),
			'status'                => $this->integer()->notNull()->defaultValue(1),
			'time'                  => $this->integer()->notNull(),
			'fine'                  => $this->integer()->notNull()->defaultValue(0),
			'resultTime'            => $this->integer()->notNull(),
			'athleteClassId'        => $this->integer(),
			'newAthleteClassId'     => $this->integer(),
			'newAthleteClassStatus' => $this->integer(),
			'percent'               => $this->double(),
			'videoLink'             => $this->text()->notNull(),
			'cancelReason'          => $this->text(),
			'stageId'               => $this->integer()->notNull(),
			'date'                  => $this->integer()->notNull(),
			'dateAdded'             => $this->integer()->notNull(),
			'dateUpdated'           => $this->integer()->notNull(),
			'cityId'                => $this->integer(),
			'countryId'             => $this->integer()->notNull()
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('RequestsForSpecialStages');
	}
}
