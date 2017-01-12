<?php

use yii\db\Migration;

/**
 * Handles the creation for table `groups_menu`.
 */
class m170112_113954_create_groups_menu_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('groups_menu', [
			'id'    => $this->primaryKey(),
			'title' => $this->string()->notNull(),
			'sort'  => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('groups_menu');
	}
}
