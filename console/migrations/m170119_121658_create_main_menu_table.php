<?php

use yii\db\Migration;

/**
 * Handles the creation for table `main_menu`.
 */
class m170119_121658_create_main_menu_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('MainMenu', [
			'id'     => $this->primaryKey(),
			'title'  => $this->string(),
			'sort'   => $this->integer()->notNull(),
			'pageId' => $this->integer(),
			'link'   => $this->string(),
			'type'   => $this->string()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('MainMenu');
	}
}
