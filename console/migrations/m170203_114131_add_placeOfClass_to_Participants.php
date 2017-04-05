<?php

use yii\db\Migration;

class m170203_114131_add_placeOfClass_to_Participants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Participant::tableName(), 'placeOfClass', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Participant::tableName(), 'placeOfClass');
    }
}
