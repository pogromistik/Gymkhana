<?php

use yii\db\Migration;

class m170315_084110_add_indexes_to_countries extends Migration
{
    public function safeUp()
    {
	    $this->createIndex('Regions_title', \common\models\Region::tableName(), 'title');
	    $this->createIndex('Cities_title', \common\models\City::tableName(), 'title');
    }

    public function safeDown()
    {
	    $this->dropIndex('Regions_title', \common\models\Region::tableName());
	    $this->dropIndex('Cities_title', \common\models\City::tableName());
    }
}
