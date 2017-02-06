<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document_sections`.
 */
class m170127_062222_create_document_sections_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable('DocumentSections', [
			'id'     => $this->primaryKey(),
			'title'  => $this->string()->notNull(),
			'status' => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable('DocumentSections');
	}
}
