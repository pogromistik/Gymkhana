<?php

use yii\db\Migration;

class m171019_080206_add_params_for_motorcycle extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Motorcycle::tableName(), 'cbm', $this->integer());
		$this->addColumn(\common\models\Motorcycle::tableName(), 'power', $this->double());
		$this->addColumn(\common\models\Motorcycle::tableName(), 'isCruiser', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Motorcycle::tableName(), 'cbm');
		$this->dropColumn(\common\models\Motorcycle::tableName(), 'power');
		$this->dropColumn(\common\models\Motorcycle::tableName(), 'isCruiser');
	}
}
