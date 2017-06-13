<?php

use yii\db\Migration;

class m170613_053748_add_cbm_to_motorcycles extends Migration
{
    public function safeUp()
    {
		$this->addColumn(\common\models\Motorcycle::tableName(), 'cbm', $this->integer());
    }

    public function safeDown()
    {
       $this->dropColumn(\common\models\Motorcycle::tableName(), 'cbm');
    }
}
