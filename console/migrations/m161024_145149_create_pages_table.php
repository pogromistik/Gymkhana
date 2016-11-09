<?php

use yii\db\Migration;

/**
 * Handles the creation for table `pages`.
 */
class m161024_145149_create_pages_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('pages', [
			'id'          => $this->primaryKey(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull(),
			'parentId'    => $this->integer(),
			'url'         => $this->string(),
			'title'       => $this->string()->notNull(),
			'keywords'    => $this->text(),
			'description' => $this->text(),
			'status'      => $this->integer()->notNull()->defaultValue(1),
			'showInMenu'  => $this->integer()->notNull()->defaultValue(0),
			'sort'        => $this->integer(),
			'layoutId'    => $this->string()->notNull()
		]);

		$this->addForeignKey('pages_layoutId', 'pages', 'layoutId', 'layouts', 'id');
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('pages');
	}
}
