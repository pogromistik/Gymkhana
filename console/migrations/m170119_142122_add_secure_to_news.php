<?php

use yii\db\Migration;

class m170119_142122_add_secure_to_news extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\News::tableName(), 'secure', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\News::tableName(), 'secure');
    }
}
