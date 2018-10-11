<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Diploms`.
 */
class m180906_095518_create_Diplomas_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('DiplomaTemplates', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string()->notNull(),
			'imgPath'     => $this->string()->notNull(),
			'config'      => $this->text()->notNull(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull(),
			'dimensions'  => $this->json()
		]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('DiplomaTemplates');
	}
}
