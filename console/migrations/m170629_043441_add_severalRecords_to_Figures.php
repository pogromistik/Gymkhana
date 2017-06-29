<?php

use yii\db\Migration;

class m170629_043441_add_severalRecords_to_Figures extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Figure::tableName(), 'severalRecords', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Figure::tableName(), 'severalRecords');
	}
}
