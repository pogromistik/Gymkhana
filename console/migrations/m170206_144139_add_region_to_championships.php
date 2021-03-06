<?php

use yii\db\Migration;

class m170206_144139_add_region_to_championships extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Championship::tableName(), 'regionId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Championship::tableName(), 'regionId');
    }
}
