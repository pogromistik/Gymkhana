<?php

use yii\db\Migration;

class m170116_082956_add_smallInfo_to_Contacts extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Contacts::tableName(), 'smallInfo', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Contacts::tableName(), 'smallInfo');
    }
}
