<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Interview`.
 */
class m180628_175641_create_Interview_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('Interviews', [
			'id'            => $this->primaryKey(),
			'dateAdded'     => $this->integer()->notNull(),
			'dateUpdated'   => $this->integer()->notNull(),
			'dateStart'     => $this->integer()->notNull(),
			'dateEnd'       => $this->integer()->notNull(),
			'title'         => $this->string()->notNull(),
			'titleEn'       => $this->string(),
			'description'   => $this->text(),
			'descriptionEn' => $this->text(),
			'onlyPictures'  => $this->integer()->notNull()->defaultValue(0),
			'showResults'   => $this->integer()->notNull()->defaultValue(0)
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('Interviews');
	}
}
