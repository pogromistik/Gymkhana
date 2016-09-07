<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mainphoto`.
 */
class m160906_142337_create_MainPhoto_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Main_Photo', [
			'id'        => $this->primaryKey(),
			'fileName'  => $this->string(),
			'type'      => $this->integer(),
			'dateAdded' => $this->integer()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Main_Photo');
	}
}
