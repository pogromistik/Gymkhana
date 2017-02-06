<?php

use yii\db\Migration;

class m170206_042806_add_region_to_City extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'region', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\City::tableName(), 'region');
    }
}
