<?php

use yii\db\Migration;

class m170614_122340_add_pointsByMoscow_to_Participants extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\Participant::tableName(), 'pointsByMoscow', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(\common\models\Participant::tableName(), 'pointsByMoscow');
    }
}
