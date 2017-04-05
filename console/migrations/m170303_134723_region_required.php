<?php

use yii\db\Migration;

class m170303_134723_region_required extends Migration
{
    public function safeUp()
    {
    	$this->alterColumn(\common\models\Stage::tableName(), 'regionId', 'SET NOT NULL');
	    $this->alterColumn(\common\models\Athlete::tableName(), 'regionId', 'SET NOT NULL');
	    $this->alterColumn(\common\models\City::tableName(), 'regionId', 'SET NOT NULL');
    }

    public function safeDown()
    {
	    $this->alterColumn(\common\models\Stage::tableName(), 'regionId', 'DROP NOT NULL');
	    $this->alterColumn(\common\models\Athlete::tableName(), 'regionId', 'DROP NOT NULL');
	    $this->alterColumn(\common\models\City::tableName(), 'regionId', 'DROP NOT NULL');
    }
}
