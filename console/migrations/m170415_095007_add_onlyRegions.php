<?php

use console\components\PGMigration;

class m170415_095007_add_onlyRegions extends PGMigration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Championship::tableName(), 'onlyRegions', $this->jsonb());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Championship::tableName(), 'onlyRegions');
    }
}
