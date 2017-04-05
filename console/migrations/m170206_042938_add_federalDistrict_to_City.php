<?php

use yii\db\Migration;

class m170206_042938_add_federalDistrict_to_City extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'federalDistrict', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\City::tableName(), 'federalDistrict');
    }
}
