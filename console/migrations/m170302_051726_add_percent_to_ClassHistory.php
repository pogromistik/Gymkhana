<?php

use yii\db\Migration;

class m170302_051726_add_percent_to_ClassHistory extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\ClassHistory::tableName(), 'percent', $this->float());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\ClassHistory::tableName(), 'percent');
    }
}
