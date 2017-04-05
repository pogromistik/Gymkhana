<?php

use yii\db\Migration;

class m170203_051312_add_countRace_to_Stages extends Migration
{
    public function safeUp()
    {
    	return $this->addColumn(\common\models\Stage::tableName(), 'countRace', $this->integer()->notNull()->defaultValue(2));
    }

    public function safeDown()
    {
    	return $this->dropColumn(\common\models\Stage::tableName(), 'countRace');
    }
}
