<?php

use yii\db\Migration;

class m170711_060512_add_translate_rule extends Migration
{
	public function safeUp()
	{
		$this->insert('auth_item', [
			'name'        => 'translate',
			'type'        => 2,
			'description' => 'Переводчик',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'translate']);
		
	}
	
	public function safeDown()
	{
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'translate']);
		$this->delete('auth_item', ['name' => 'translate']);
	}
}
