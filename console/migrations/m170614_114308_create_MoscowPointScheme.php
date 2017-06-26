<?php

use yii\db\Migration;

class m170614_114308_create_MoscowPointScheme extends Migration
{
	public function safeUp()
	{
		$this->createTable('MoscowPoints', [
			'id' => $this->primaryKey(),
			'class' => $this->integer()->notNull(),
			'place' => $this->integer()->notNull(),
			'point' => $this->integer()->notNull()
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('MoscowPoints');
	}
}
