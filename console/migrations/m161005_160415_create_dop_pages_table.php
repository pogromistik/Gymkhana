<?php

use yii\db\Migration;

/**
 * Handles the creation for table `dop_pages`.
 */
class m161005_160415_create_dop_pages_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('dop_pages', [
			'id'      => $this->primaryKey(),
			'title'   => $this->string()->notNull(),
			'picture' => $this->string(),
			'type'    => $this->integer()->notNull()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('dop_pages');
	}
}
