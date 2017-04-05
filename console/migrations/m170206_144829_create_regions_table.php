<?php

use yii\db\Migration;

/**
 * Handles the creation of table `regions`.
 */
class m170206_144829_create_regions_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Regions', [
			'id'    => $this->primaryKey(),
			'title' => $this->string()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Regions');
	}
}
