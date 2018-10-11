<?php

use yii\db\Migration;

/**
 * Handles the creation of table `Diplomas`.
 */
class m180910_115159_create_Diplomas_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('Diplomas', [
			'id'          => $this->primaryKey(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateUpdated' => $this->integer()->notNull(),
			'templateId'  => $this->integer()->notNull(),
			'modelClass'  => $this->string()->notNull(),
			'modelId'     => $this->integer()->notNull()
		]);
	
		$this->addForeignKey("Diplomas_templateId", "Diplomas", "templateId", \common\models\DiplomaTemplate::tableName(), "id", 'CASCADE', 'CASCADE');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('Diplomas');
	}
}
