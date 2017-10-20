<?php

use yii\db\Migration;

class m171017_044348_add_points_to_request extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\RequestForSpecialStage::tableName(), 'points', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\RequestForSpecialStage::tableName(), 'points');
	}
}
