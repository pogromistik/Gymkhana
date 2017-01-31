<?php

use yii\db\Migration;

class m170131_112954_create_regional_groups_table extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function up()
	{
		$this->createTable('regional_groups', [
			'id'            => $this->primaryKey(),
			'title'         => $this->string()->notNull()
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function down()
	{
		$this->dropTable('regional_groups');
	}
}
