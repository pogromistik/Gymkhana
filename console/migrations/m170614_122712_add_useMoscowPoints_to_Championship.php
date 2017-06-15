<?php

use yii\db\Migration;

class m170614_122712_add_useMoscowPoints_to_Championship extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Championship::tableName(), 'useMoscowPoints', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Championship::tableName(), 'useMoscowPoints');
	}
}
