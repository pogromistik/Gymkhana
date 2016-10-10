<?php

use yii\db\Migration;

/**
 * Handles the creation for table `albums`.
 */
class m161010_153129_create_albums_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('albums', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string()->notNull(),
			'yearId'      => $this->integer()->notNull(),
			'folder'      => $this->string(),
			'cover'       => $this->string(),
			'dateAdded'   => $this->integer()->notNull(),
			'description' => $this->string()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('albums');
	}
}
