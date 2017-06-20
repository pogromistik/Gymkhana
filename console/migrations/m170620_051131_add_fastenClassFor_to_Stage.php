<?php

use yii\db\Migration;

class m170620_051131_add_fastenClassFor_to_Stage extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\Stage::tableName(),'fastenClassFor', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(\common\models\Stage::tableName(), 'fastenClassFor');
    }
}
