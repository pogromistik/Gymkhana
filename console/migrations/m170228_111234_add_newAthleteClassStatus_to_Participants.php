<?php

use yii\db\Migration;

class m170228_111234_add_newAthleteClassStatus_to_Participants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Participant::tableName(), 'newAthleteClassStatus', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Participant::tableName(), 'newAthleteClassStatus');
    }
}
