<?php

use yii\db\Migration;

class m170117_105511_add_showInRussiaPage_to_Cities extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\City::tableName(), 'showInRussiaPage', $this->smallInteger()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\City::tableName(), 'showInRussiaPage');
    }
}
