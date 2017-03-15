<?php

use yii\db\Migration;

class m170315_160711_add_country_to_Stages extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Stage::tableName(), 'countryId', $this->integer()->notNull()->defaultValue(1));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Stage::tableName(), 'countryId');
    }
}
