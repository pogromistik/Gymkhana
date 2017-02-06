<?php

use yii\db\Migration;

/**
 * Handles the creation for table `menu_items`.
 */
class m170112_114027_create_menu_items_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('MenuItems', [
			'id'          => $this->primaryKey(),
			'groupsMenuId' => $this->integer(),
			'title'       => $this->string(),
			'sort'        => $this->integer()->notNull(),
			'pageId'      => $this->integer(),
			'link'        => $this->string()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('MenuItems');
	}
}
