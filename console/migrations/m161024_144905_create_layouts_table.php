<?php

use yii\db\Migration;

/**
 * Handles the creation for table `layouts`.
 */
class m161024_144905_create_layouts_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('layouts', [
			'id'    => $this->string()->notNull(),
			'title' => $this->string()->notNull()
		]);

		$this->addPrimaryKey('layouts_id', 'layouts', 'id');
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('layouts');
	}
}
