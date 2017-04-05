<?php

use yii\db\Migration;

class m170323_124902_add_sort_to_overall_files extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\OverallFile::tableName(), 'sort', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\OverallFile::tableName(), 'sort');
    }
}
