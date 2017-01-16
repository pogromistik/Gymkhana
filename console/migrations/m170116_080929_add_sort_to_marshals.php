<?php

use yii\db\Migration;

class m170116_080929_add_sort_to_marshals extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Marshal::tableName(), 'sort', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Marshal::tableName(), 'sort');
    }
}
