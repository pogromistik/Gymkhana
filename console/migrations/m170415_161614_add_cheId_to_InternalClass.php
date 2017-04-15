<?php

use yii\db\Migration;

class m170415_161614_add_cheId_to_InternalClass extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\InternalClass::tableName(), 'cheId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\InternalClass::tableName(), 'cheId');
    }
}
