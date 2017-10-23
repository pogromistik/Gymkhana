<?php

use yii\db\Migration;

class m171016_090402_add_place_to_RequestsForSpecialStages extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\RequestForSpecialStage::tableName(), 'place', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\RequestForSpecialStage::tableName(), 'place');
	}
}
