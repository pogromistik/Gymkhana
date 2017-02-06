<?php

use yii\db\Migration;

/**
 * Handles the creation for table `contacts`.
 */
class m161005_154750_create_contacts_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('Contacts', [
			'id'       => $this->primaryKey(),
			'phone'    => $this->string()->notNull(),
			'email'    => $this->string(),
			'addr'     => $this->string()->notNull(),
			'time'     => $this->text()->notNull(),
			'card'     => $this->string(),
			'cardInfo' => $this->string()
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('Contacts');
	}
}
