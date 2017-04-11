<?php

use yii\db\Migration;

class m170411_111232_add_creatorUserId_to_Athlete extends Migration
{
    public function up()
    {
		$this->addColumn(\common\models\Athlete::tableName(), 'creatorUserId', $this->integer());
    }

    public function down()
    {
        $this->dropColumn(\common\models\Athlete::tableName(), 'creatorUserId');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
