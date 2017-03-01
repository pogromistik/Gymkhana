<?php

use yii\db\Migration;

class m170228_114907_add_coefficient_to_AthleteClasses extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\AthletesClass::tableName(), 'coefficient', $this->float());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\AthletesClass::tableName(), 'coefficient');
    }
}
