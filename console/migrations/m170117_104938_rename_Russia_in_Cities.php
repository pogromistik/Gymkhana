<?php

use yii\db\Migration;

class m170117_104938_rename_Russia_in_Cities extends Migration
{
    public function safeUp()
    {
    	$this->renameTable('russia', 'cities');
    }

    public function safeDown()
    {
    	$this->renameTable('cities', 'russia');
    }
}
