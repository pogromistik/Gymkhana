<?php

use yii\db\Migration;

class m170315_052234_add_state_to_city extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'state', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\City::tableName(), 'state');
    }
}
