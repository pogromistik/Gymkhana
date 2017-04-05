<?php

use yii\db\Migration;

class m170210_141411_add_numbers_to_Championships extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Championship::tableName(), 'minNumber', $this->integer()->notNull()->defaultValue(1));
	    $this->addColumn(\common\models\Championship::tableName(), 'maxNumber', $this->integer()->notNull()->defaultValue(99));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Championship::tableName(), 'minNumber');
    	$this->dropColumn(\common\models\Championship::tableName(), 'maxNumber');
    }
}
