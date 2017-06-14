<?php

use yii\db\Migration;

class m170614_043102_add_showResults_to_Championships extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Championship::tableName(), 'showResults', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Championship::tableName(), 'showResults');
	}
}
