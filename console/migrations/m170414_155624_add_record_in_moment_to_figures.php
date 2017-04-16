<?php

use yii\db\Migration;

class m170414_155624_add_record_in_moment_to_figures extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\FigureTime::tableName(), 'recordInMoment', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\FigureTime::tableName(), 'recordInMoment');
    }
}
