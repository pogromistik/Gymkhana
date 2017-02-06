<?php

use yii\db\Migration;

class m170117_104938_rename_Russia_in_Cities extends Migration
{
    public function safeUp()
    {
    	$this->renameTable('Russia', 'Cities');
    }

    public function safeDown()
    {
    	$this->renameTable('Cities', 'Russia');
    }
}
