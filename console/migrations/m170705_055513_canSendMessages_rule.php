<?php

use yii\db\Migration;

class m170705_055513_canSendMessages_rule extends Migration
{
    public function safeUp()
    {
	    $this->insert('auth_item', [
		    'name'        => 'canSendMessages',
		    'type'        => 2,
		    'description' => 'Может слать сообщения на почту',
		    'created_at'  => time(),
		    'updated_at'  => time()
	    ]);
	    $this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canSendMessages']);
	
    }

    public function safeDown()
    {
	    $this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canSendMessages']);
	    $this->delete('auth_item', ['name' => 'canSendMessages']);
    }
}
