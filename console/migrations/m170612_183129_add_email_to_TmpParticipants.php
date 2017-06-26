<?php

use yii\db\Migration;

class m170612_183129_add_email_to_TmpParticipants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\TmpParticipant::tableName(), 'email', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\TmpParticipant::tableName(), 'email');
    }
}
