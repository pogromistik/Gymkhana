<?php

use yii\db\Migration;

class m170313_040858_add_cancelReason_to_TmpFiguresResults extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\TmpFigureResult::tableName(), 'cancelReason', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\TmpFigureResult::tableName(), 'cancelReason');
    }
}
