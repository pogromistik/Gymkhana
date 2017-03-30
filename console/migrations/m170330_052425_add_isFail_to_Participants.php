<?php

use yii\db\Migration;

class m170330_052425_add_isFail_to_Participants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Time::tableName(), 'isFail', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Time::tableName(), 'isFail');
    }
}
