<?php

use yii\db\Migration;

class m170110_151836_add_pageId_to_News extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\News::tableName(), 'pageId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\News::tableName(), 'pageId');
    }
}
