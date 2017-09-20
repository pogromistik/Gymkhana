<?php

use yii\db\Migration;

class m170628_085929_add_actualPercent_to_FigureTimes extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\FigureTime::tableName(), 'actualPercent', $this->double());
    }

    public function safeDown()
    {
        $this->dropColumn(\common\models\FigureTime::tableName(), 'actualPercent');
    }
}
