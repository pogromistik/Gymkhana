<?php

use yii\db\Migration;

class m170303_134013_add_regionId_to_Stages extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Stage::tableName(), 'regionId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Stage::tableName(), 'regionId');
    }
}
