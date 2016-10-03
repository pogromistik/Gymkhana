<?php

use yii\db\Migration;

/**
 * Handles the creation for table `regular`.
 */
class m161003_152552_create_regular_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('regular', [
			'id'   => $this->primaryKey(),
			'text' => $this->text()->notNull(),
			'sort' => $this->integer()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('regular');
	}
}
