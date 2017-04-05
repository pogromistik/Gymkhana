<?php

use yii\db\Migration;

class m170228_092131_add_newAthleteClassId_to_Participants extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Participant::tableName(), 'newAthleteClassId', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Participant::tableName(), 'newAthleteClassId');
	}
}
