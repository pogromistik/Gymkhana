<?php

use yii\db\Migration;

class m170315_042317_add_titles_to_countries extends Migration
{
    public function safeUp()
    {
	    $this->addColumn(\common\models\Country::tableName(), 'title_en', $this->string());
	    $this->addColumn(\common\models\Country::tableName(), 'title_original', $this->string());
    }

    public function safeDown()
    {
	    $this->dropColumn(\common\models\Country::tableName(), 'title_en');
	    $this->dropColumn(\common\models\Country::tableName(), 'title_original');
    }
}
