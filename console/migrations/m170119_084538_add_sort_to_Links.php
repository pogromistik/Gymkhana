<?php

use yii\db\Migration;

class m170119_084538_add_sort_to_Links extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Link::tableName(), 'sort', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Link::tableName(), 'sort');
    }
}
