<?php

use yii\db\Migration;

class m170731_143444_add_canApproveFigureResults_rule extends Migration
{
    public function safeUp()
    {
	    $this->insert('auth_item', [
		    'name'        => 'canApproveFigureResults',
		    'type'        => 1,
		    'description' => 'Может подтверждать присланные результаты фигур',
		    'created_at'  => time(),
		    'updated_at'  => time()
	    ]);
	    $this->insert('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canApproveFigureResults']);
    }

    public function safeDown()
    {
	    $this->delete('auth_item_child', ['parent' => 'globalWorkWithCompetitions', 'child' => 'canApproveFigureResults']);
	    $this->delete('auth_item', ['name' => 'canApproveFigureResults']);
    }
}
