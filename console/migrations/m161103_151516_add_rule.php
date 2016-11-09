<?php

use yii\db\Migration;

class m161103_151516_add_rule extends Migration
{
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name'        => 'developer',
            'type'        => 1,
            'description' => 'Разработчик',
            'created_at'  => time(),
            'updated_at'  => time()
        ]);
        $this->insert('auth_item_child', ['parent' => 'developer', 'child' => 'admin']);
    }
    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'developer']);
    }
}
