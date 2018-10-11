<?php

use yii\db\Migration;

/**
 * Class m180903_040809_add_role_yura
 */
class m180903_040809_add_role_yura extends Migration
{
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name'        => 'yura',
            'type'        => 2,
            'description' => 'Юра',
            'created_at'  => time(),
            'updated_at'  => time()
        ]);
        $this->insert('auth_item_child', ['parent' => 'yura', 'child' => 'globalWorkWithCompetitions']);
        $this->insert('auth_item_child', ['parent' => 'developer', 'child' => 'yura']);
    }

    public function safeDown()
    {
        $this->delete('auth_item_child', ['parent' => 'developer', 'child' => 'yura']);
        $this->delete('auth_item_child', ['parent' => 'yura', 'child' => 'globalWorkWithCompetitions']);
        $this->delete('auth_item', ['name' => 'yura']);
    }
}
