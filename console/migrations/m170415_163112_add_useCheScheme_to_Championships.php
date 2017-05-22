<?php

use yii\db\Migration;

class m170415_163112_add_useCheScheme_to_Championships extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Championship::tableName(), 'useCheScheme', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Championship::tableName(), 'useCheScheme');
    }
}
