<?php

use yii\db\Migration;

/**
 * Handles the creation of table `assoc_news`.
 */
class m170126_121223_create_assoc_news_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('AssocNews', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string(),
			'previewText' => $this->text()->notNull(),
			'fullText'    => $this->text(),
			'link'        => $this->string(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull(),
			'datePublish' => $this->integer(),
			'secure'      => $this->integer()->notNull()->defaultValue(0)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('AssocNews');
	}
}
