<?php

use yii\db\Migration;

class m170314_092952_add_country_to_cities extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'countryId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\City::tableName(), 'countryId');
    }
}
