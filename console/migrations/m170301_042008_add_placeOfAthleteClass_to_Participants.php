<?php

use yii\db\Migration;

class m170301_042008_add_placeOfAthleteClass_to_Participants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Participant::tableName(), 'placeOfAthleteClass', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Participant::tableName(), 'placeOfAthleteClass');
    }
}
