<?php

use yii\db\Migration;

class m170607_074840_add_video_to_Figures extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\FigureTime::tableName(), 'videoLink', $this->string());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\FigureTime::tableName(), 'videoLink');
    }
}
