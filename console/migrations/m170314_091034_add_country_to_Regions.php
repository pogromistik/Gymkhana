<?php

use yii\db\Migration;

class m170314_091034_add_country_to_Regions extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Region::tableName(), 'countryId', $this->integer());
    }

    public function safeDown()
    {
	    $this->dropColumn(\common\models\Region::tableName(), 'countryId');
    }
}
