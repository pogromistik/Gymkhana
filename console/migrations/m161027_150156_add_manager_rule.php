<?php

use yii\db\Migration;

class m161027_150156_add_manager_rule extends Migration
{
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name'        => 'manager',
            'type'        => 2,
            'description' => 'Менеджер',
            'created_at'  => time(),
            'updated_at'  => time()
        ]);
        $this->insert('auth_item_child', ['parent' => 'admin', 'child' => 'manager']);
    }
    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'manager']);
    }
}
