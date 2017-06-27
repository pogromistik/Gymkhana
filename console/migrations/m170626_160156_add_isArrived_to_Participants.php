<?php

use yii\db\Migration;

class m170626_160156_add_isArrived_to_Participants extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\Participant::tableName(), 'isArrived', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn(\common\models\Participant::tableName(), 'isArrived');
    }
}
