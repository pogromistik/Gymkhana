<?php

use yii\db\Migration;

class m170404_075820_add_rules_for_championship extends Migration
{
	public function safeUp()
	{
		$this->insert('auth_item', [
			'name'        => 'globalWorkWithCompetitions',
			'type'        => 1,
			'description' => 'Может редактировать всю информацию по соревнованиям',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'globalWorkWithCompetitions']);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'competitions']);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'projectOrganizer']);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'projectAdmin']);
		$this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'refereeOfCompetitions']);
		
		$this->insert('auth_item', [
			'name'        => 'projectOrganizer',
			'type'        => 1,
			'description' => 'Организатор проекта',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'projectOrganizer']);
		$this->insert('auth_item_child', ['parent' => 'projectOrganizer', 'child' => 'competitions']);
		$this->insert('auth_item_child', ['parent' => 'projectOrganizer', 'child' => 'projectAdmin']);
		
		$this->insert('auth_item', [
			'name'        => 'projectAdmin',
			'type'        => 1,
			'description' => 'Администратор проекта',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'projectAdmin']);
		$this->insert('auth_item_child', ['parent' => 'projectAdmin', 'child' => 'competitions']);
		$this->insert('auth_item_child', ['parent' => 'projectAdmin', 'child' => 'refereeOfCompetitions']);
		
		$this->insert('auth_item', [
			'name'        => 'refereeOfCompetitions',
			'type'        => 1,
			'description' => 'Судья соревнований',
			'created_at'  => time(),
			'updated_at'  => time()
		]);
		$this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'refereeOfCompetitions']);
		$this->insert('auth_item_child', ['parent' => 'refereeOfCompetitions', 'child' => 'competitions']);
	}
	public function safeDown()
	{
		$this->delete('auth_item', ['name' => 'globalWorkWithCompetitions']);
		$this->delete('auth_item', ['name' => 'projectOrganizer']);
		$this->delete('auth_item', ['name' => 'projectAdmin']);
		$this->delete('auth_item', ['name' => 'refereeOfCompetitions']);
		$this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'refereeOfCompetitions']);
		$this->delete('auth_item_child', ['parent' => 'refereeOfCompetitions', 'child' => 'competitions']);
		$this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'projectAdmin']);
		$this->delete('auth_item_child', ['parent' => 'projectAdmin', 'child' => 'competitions']);
		$this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'projectOrganizer']);
		$this->delete('auth_item_child', ['parent' => 'projectOrganizer', 'child' => 'competitions']);
		$this->delete('auth_item_child', ['parent' => 'admin', 'child' => 'globalWorkWithCompetitions']);
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'competitions']);
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'projectOrganizer']);
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'projectAdmin']);
		$this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'refereeOfCompetitions']);
		$this->delete('auth_item_child', ['parent' => 'projectOrganizer', 'child' => 'projectAdmin']);
		$this->delete('auth_item_child', ['parent' => 'projectAdmin', 'child' => 'refereeOfCompetitions']);
	}
}
