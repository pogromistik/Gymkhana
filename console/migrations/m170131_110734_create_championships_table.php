<?php

use yii\db\Migration;

/**
 * Handles the creation of table `championships`.
 */
class m170131_110734_create_championships_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Championships', [
			'id'            => $this->primaryKey(),
			'title'         => $this->string(),
			'description'   => $this->text(),
			'yearId'        => $this->integer()->notNull(),
			'status'        => $this->integer()->notNull()->defaultValue(1),
			'groupId'       => $this->integer()->notNull(),
			'regionGroupId' => $this->integer(),
			'dateAdded'     => $this->integer()->notNull(),
			'dateUpdated'   => $this->integer()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Championships');
	}
}
