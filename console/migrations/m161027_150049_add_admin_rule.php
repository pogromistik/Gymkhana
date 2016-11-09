<?php

use yii\db\Migration;

class m161027_150049_add_admin_rule extends Migration
{
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name'        => 'admin',
            'type'        => 1,
            'description' => 'Администратор',
            'created_at'  => time(),
            'updated_at'  => time()
        ]);
    }
    public function safeDown()
    {
        $this->delete('auth_item', ['name' => 'admin']);
    }
}
