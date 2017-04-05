<?php

use yii\db\Migration;

/**
 * Handles the creation of table `countries`.
 */
class m170314_082725_create_countries_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Countries', [
			'id'    => $this->primaryKey(),
			'title' => $this->string()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Countries');
	}
}
