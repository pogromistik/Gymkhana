<?php

use yii\db\Migration;

class m170311_152529_add_trackPhoto_to_Stage extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Stage::tableName(), 'trackPhoto', $this->string());
    	$this->addColumn(\common\models\Stage::tableName(), 'trackPhotoStatus', $this->integer());
    }

    public function safeDown()
    {
	    $this->dropColumn(\common\models\Stage::tableName(), 'trackPhoto');
	    $this->dropColumn(\common\models\Stage::tableName(), 'trackPhotoStatus');
    }
}
