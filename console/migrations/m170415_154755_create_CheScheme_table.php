<?php

use yii\db\Migration;

/**
 * Handles the creation of table `CheScheme`.
 */
class m170415_154755_create_CheScheme_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('CheScheme', [
			'id'          => $this->primaryKey(),
			'title'       => $this->string(),
			'description' => $this->string(),
			'percent'     => $this->double()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('CheScheme');
	}
}
