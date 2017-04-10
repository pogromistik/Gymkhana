<?php

use yii\db\Migration;

class m170408_165744_add_useForClassesCalculate extends Migration
{
    public function safeUp()
    {
	    $this->alterColumn(\common\models\Figure::tableName(), 'bestTime', 'DROP NOT NULL');
	    $this->addColumn(\common\models\Figure::tableName(), 'useForClassesCalculate', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
    	$this->alterColumn(\common\models\Figure::tableName(), 'bestTime', 'SET NOT NULL');
    	$this->dropColumn(\common\models\Figure::tableName(), 'useForClassesCalculate');
    }
}
