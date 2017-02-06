<?php

use yii\db\Migration;

/**
 * Handles the creation for table `marshals`.
 */
class m161003_165532_create_marshals_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Marshals', [
			'id'              => $this->primaryKey(),
			'name'            => $this->string()->notNull(),
			'post'            => $this->string(),
			'photo'           => $this->string(),
			'text1'           => $this->text(),
			'text2'           => $this->text(),
			'text3'           => $this->text(),
			'motorcycle'      => $this->string(),
			'motorcyclePhoto' => $this->string(),
			'gif'             => $this->string(),
			'link'            => $this->string()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Marshals');
	}
}
