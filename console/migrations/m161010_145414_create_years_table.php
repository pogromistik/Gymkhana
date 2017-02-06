<?php

use yii\db\Migration;

/**
 * Handles the creation for table `years`.
 */
class m161010_145414_create_years_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Years', [
			'id'     => $this->primaryKey(),
			'year'   => $this->integer()->notNull(),
			'status' => $this->integer()->notNull()->defaultValue(1)
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Years');
	}
}
