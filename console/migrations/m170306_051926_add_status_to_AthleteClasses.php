<?php

use yii\db\Migration;

class m170306_051926_add_status_to_AthleteClasses extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\AthletesClass::tableName(), 'status', $this->integer()->notNull()->defaultValue(1));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\AthletesClass::tableName(), 'status');
	}
}
