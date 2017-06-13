<?php

use yii\db\Migration;

class m170613_061104_add_motorcycleCbm_to_TmpParticipants extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\TmpParticipant::tableName(), 'motorcycleCbm', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\TmpParticipant::tableName(), 'motorcycleCbm');
	}
}
