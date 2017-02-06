<?php

use yii\db\Migration;

/**
 * Handles the creation for table `russia`.
 */
class m161007_155437_create_russia_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Russia', [
			'id'    => $this->primaryKey(),
			'title' => $this->string()->notNull(),
			'link'  => $this->string(),
			'top'   => $this->float(),
			'left'  => $this->float()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Russia');
	}
}
