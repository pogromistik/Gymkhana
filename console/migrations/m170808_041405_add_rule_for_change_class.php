<?php

use yii\db\Migration;

class m170808_041405_add_rule_for_change_class extends Migration
{
	public function safeUp()
	{
		$this->insert('auth_item', [
			'name'        => 'canChangeClass',
			'type'        => 1,
			'description' => 'Может менять класс спортсмену',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canChangeClass']);
	}
	
	public function safeDown()
	{
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canChangeClass']);
		$this->delete('auth_item', ['name' => 'canChangeClass']);
	}
}
