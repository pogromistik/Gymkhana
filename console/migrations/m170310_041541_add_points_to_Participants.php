<?php

use yii\db\Migration;

class m170310_041541_add_points_to_Participants extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Participant::tableName(), 'points', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Participant::tableName(), 'points');
	}
}
