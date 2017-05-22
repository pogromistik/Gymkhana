<?php

use yii\db\Migration;

class m170420_100045_drop_notNull_datePublish extends Migration
{
    public function safeUp()
    {
	    $this->alterColumn(\common\models\News::tableName(), 'datePublish', 'DROP NOT NULL');
    }

    public function safeDown()
    {
	    $this->alterColumn(\common\models\News::tableName(), 'datePublish', 'SET NOT NULL');
    }
}
