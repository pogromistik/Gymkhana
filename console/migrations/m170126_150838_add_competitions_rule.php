<?php

use yii\db\Migration;

class m170126_150838_add_competitions_rule extends Migration
{
	public function safeUp()
	{
		$this->insert('auth_item', [
			'name'        => 'competitions',
			'type'        => 2,
			'description' => 'Управляет соревнованиями',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'competitions']);
	}
	
	public function safeDown()
	{
		$this->delete('auth_item', ['name' => 'competitions']);
	}
}
