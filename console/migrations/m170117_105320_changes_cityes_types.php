<?php

use yii\db\Migration;

class m170117_105320_changes_cityes_types extends Migration
{
    public function safeUp()
    {
	    $this->alterColumn(\common\models\City::tableName(), 'link', $this->string());
    }

    public function safeDown()
    {
    	return true;
    }
}
