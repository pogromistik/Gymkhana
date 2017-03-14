<?php

use yii\db\Migration;

class m170314_124631_add_cities_indexes extends Migration
{
    public function safeUp()
    {
	    $this->addForeignKey('City_countryId', \common\models\City::tableName(), 'countryId', \common\models\Country::tableName(), 'id');
	    $this->addForeignKey('City_regionId', \common\models\City::tableName(), 'regionId', \common\models\Region::tableName(), 'id');
	    $this->addForeignKey('Region_countryId', \common\models\Region::tableName(), 'countryId', \common\models\Country::tableName(), 'id');
	    
	    $this->createIndex('City_title', \common\models\City::tableName(), 'title');
	    $this->createIndex('Region_title', \common\models\Region::tableName(), 'title');
	    $this->createIndex('Country_title', \common\models\Country::tableName(), 'title');
    }

    public function safeDown()
    {
    }
}
