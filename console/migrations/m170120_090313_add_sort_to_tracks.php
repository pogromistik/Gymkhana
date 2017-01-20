<?php

use yii\db\Migration;

class m170120_090313_add_sort_to_tracks extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Track::tableName(), 'sort', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Track::tableName(), 'sort');
    }
}
