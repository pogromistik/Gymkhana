<?php

use yii\db\Migration;

class m170307_084303_add_isNewRecord_to_FigureTimes extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\FigureTime::tableName(), 'recordType', $this->integer());
    	$this->addColumn(\common\models\FigureTime::tableName(), 'recordStatus', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\FigureTime::tableName(), 'recordType');
    	$this->dropColumn(\common\models\FigureTime::tableName(), 'recordStatus');
    }
}
