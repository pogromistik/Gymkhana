<?php

use yii\db\Migration;

class m170313_040823_add_figureResultId_to_TmpFiguresResults extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\TmpFigureResult::tableName(), 'figureResultId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\TmpFigureResult::tableName(), 'figureResultId');
    }
}
