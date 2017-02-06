<?php

use yii\db\Migration;

/**
 * Handles the creation for table `tracks`.
 */
class m170120_045312_create_tracks_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('Tracks', [
			'id'          => $this->primaryKey(),
			'photoPath'   => $this->string(),
			'documentId'  => $this->integer(),
			'description' => $this->text()->notNull(),
			'title'       => $this->string()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('Tracks');
	}
}
