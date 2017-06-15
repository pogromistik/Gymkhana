<?php

use yii\db\Migration;

class m170612_153035_add_participantsLimit_to_Stage extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Stage::tableName(), 'participantsLimit', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Stage::tableName(), 'participantsLimit');
	}
}
