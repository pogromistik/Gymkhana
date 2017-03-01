<?php

use yii\db\Migration;

class m170228_114425_add_referenceTime_to_Stage extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Stage::tableName(), 'referenceTime', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Stage::tableName(), 'referenceTime');
    }
}
