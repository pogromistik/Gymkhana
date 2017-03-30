<?php

use yii\db\Migration;

class m170315_154844_add_countryId_to_Participants extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\TmpParticipant::tableName(), 'countryId', $this->integer()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\TmpParticipant::tableName(), 'countryId');
    }
}
