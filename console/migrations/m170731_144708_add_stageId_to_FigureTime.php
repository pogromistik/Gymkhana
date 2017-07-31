<?php

use yii\db\Migration;

class m170731_144708_add_stageId_to_FigureTime extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\FigureTime::tableName(), 'stageId', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn(\common\models\FigureTime::tableName(), 'stageId');
    }
}
