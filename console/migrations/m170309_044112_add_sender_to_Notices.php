<?php

use yii\db\Migration;

class m170309_044112_add_sender_to_Notices extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Notice::tableName(), 'senderId',
		    $this->integer()->notNull()->defaultValue(-1));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Notice::tableName(), 'senderId');
    }
}
