<?php

use yii\db\Migration;

class m171012_060557_add_changeSpecialChamps extends Migration
{
	public function safeUp()
	{
		$this->insert('auth_item', [
			'name'        => 'changeSpecialChamps',
			'type'        => 1,
			'description' => 'Может редактировать особенные чемпионаты',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'changeSpecialChamps']);
	}
	
	public function safeDown()
	{
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'changeSpecialChamps']);
		$this->delete('auth_item', ['name' => 'changeSpecialChamps']);
	}
}
