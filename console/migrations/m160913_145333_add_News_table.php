<?php

use yii\db\Migration;

class m160913_145333_add_News_table extends Migration
{
	public function up()
	{
		$this->createTable('News', [
			'id'           => $this->primaryKey(),
			'title'        => $this->string()->notNull(),
			'dateCreated'  => $this->integer()->notNull(),
			'datePublish'  => $this->integer()->notNull(),
			'dateUpdated'  => $this->integer()->notNull(),
			'previewText'  => $this->text(),
			'previewImage' => $this->string(),
			'isPublish'    => $this->integer()->notNull()->defaultValue(1)
		]);
	}

	public function down()
	{
		$this->dropTable('News');
	}
}
