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
		$this->createTable('AthletesClasses', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string()->notNull(),
			'percent'     => $this->double()->notNull(),
			'sort'        => $this->integer()->notNull(),
			'description' => $this->text()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('AthletesClasses');
	}
}
