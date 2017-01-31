<?php

use yii\db\Migration;

/**
 * Handles the creation of table `motorcycles`.
 */
class m170130_071657_create_motorcycles_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('motorcycles', [
			'id'              => $this->primaryKey(),
			'athleteId'       => $this->integer()->notNull(),
			'mark'            => $this->string()->notNull(),
			'model'           => $this->string()->notNull(),
			'internalClassId' => $this->integer(),
			'dateAdded'       => $this->integer()->notNull(),
			'dateUpdated'     => $this->integer()->notNull(),
			'status'          => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('motorcycles');
	}
}
