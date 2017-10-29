<?php

use console\components\PGMigration;

/**
 * Handles the creation of table `NewsSubscriptions`.
 */
class m171019_123655_create_NewsSubscriptions_table extends PGMigration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('NewsSubscriptions', [
			'id'         => $this->primaryKey(),
			'athleteId'  => $this->integer()->notNull(),
			'regionIds'  => $this->jsonb(),
			'countryIds' => $this->jsonb(),
			'dateAdded'  => $this->integer()->notNull(),
			'types'      => $this->jsonb(),
			'dateEnd'    => $this->integer(),
			'isActive'   => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('NewsSubscriptions');
	}
}
