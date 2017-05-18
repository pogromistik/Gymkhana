<?php

use yii\db\Migration;

class m170404_115458_add_regionId_to_User extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\User::tableName(), 'regionId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\User::tableName(), 'regionId');
    }
}
