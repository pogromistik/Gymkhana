<?php

use yii\db\Migration;

class m170207_082809_add_percent_to_Participant extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Participant::tableName(), 'percent', $this->double());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Participant::tableName(), 'percent');
    }
}
