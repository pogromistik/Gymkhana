<?php

use yii\db\Migration;

class m170329_085834_add_timezone_to_cities extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'timezone', $this->string());
    	$this->addColumn(\common\models\City::tableName(), 'utc', $this->string());
    }

    public function safeDown()
    {
	    $this->dropColumn(\common\models\City::tableName(), 'timezone');
	    $this->dropColumn(\common\models\City::tableName(), 'utc');
    }
}
