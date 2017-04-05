<?php

use yii\db\Migration;

class m170310_041558_add_columns_to_Championships extends Migration
{
    public function safeUp()
    {
	    $this->addColumn(\common\models\Championship::tableName(), 'amountForAthlete', $this->integer()->notNull()->defaultValue(1));
	    $this->addColumn(\common\models\Championship::tableName(), 'requiredOtherRegions', $this->integer()->notNull()->defaultValue(0));
	    $this->addColumn(\common\models\Championship::tableName(), 'estimatedAmount', $this->integer()->notNull()->defaultValue(1));

    }

    public function safeDown()
    {
	    $this->dropColumn(\common\models\Championship::tableName(), 'amountForAthlete');
	    $this->dropColumn(\common\models\Championship::tableName(), 'requiredOtherRegions');
	    $this->dropColumn(\common\models\Championship::tableName(), 'estimatedAmount');
    }
}
