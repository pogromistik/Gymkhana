<?php

use yii\db\Migration;

class m170310_151123_add_photo_to_Athlete extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Athlete::tableName(), 'photo', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Athlete::tableName(), 'photo');
    }
}
