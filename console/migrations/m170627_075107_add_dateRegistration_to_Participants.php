<?php

use yii\db\Migration;

class m170627_075107_add_dateRegistration_to_Participants extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Participant::tableName(), 'dateRegistration', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Participant::tableName(), 'dateRegistration');
	}
}
