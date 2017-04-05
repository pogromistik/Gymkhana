<?php

use yii\db\Migration;

class m170320_152138_add_file_to_Stages extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Stage::tableName(), 'documentId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Stage::tableName(), 'documentId');
    }
}
