<?php

use yii\db\Migration;

class m170314_105458_add_country_to_athletes extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\Athlete::tableName(), 'countryId', $this->integer());
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\Athlete::tableName(), 'countryId');
    }
}
