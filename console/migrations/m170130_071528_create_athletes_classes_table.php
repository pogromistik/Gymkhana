<?php

use yii\db\Migration;

/**
 * Handles the creation of table `global_classes`.
 */
class m170130_071528_create_athletes_classes_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('athletes_classes', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string()->notNull(),
			'percent'     => $this->integer()->notNull(),
			'sort'        => $this->integer()->notNull(),
			'description' => $this->text()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('athletes_classes');
	}
}
