<?php

use console\components\PGMigration;

/**
 * Handles the creation of table `NewsSubscriptions`.
 */
class m170915_071227_create_NewsSubscriptions_table extends PGMigration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('NewsSubscriptions', [
			'id'        => $this->primaryKey(),
			'athleteId' => $this->integer()->notNull(),
			'regionIds' => $this->jsonb(),
			'dateAdded' => $this->integer()->notNull(),
			'type'      => $this->integer()->notNull()->defaultValue(1),
			'dateEnd'   => $this->integer(),
			'isActive'  => $this->integer()->notNull()->defaultValue(1)
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
