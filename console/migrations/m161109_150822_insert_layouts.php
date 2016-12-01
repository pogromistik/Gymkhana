<?php

use yii\db\Migration;

class m161109_150822_insert_layouts extends Migration
{
    public function safeUp()
    {
        $this->insert(\common\models\Layout::tableName(), ['id' => 'main', 'title' => 'Главная страница']);
        
    }

    public function safeDown()
    {
        return true;
    }
}
