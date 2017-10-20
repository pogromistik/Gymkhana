<?php

use yii\db\Migration;

class m171019_123654_add_params_to_tmpParticipants extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\TmpParticipant::tableName(), 'cbm', $this->integer());
		$this->addColumn(\common\models\TmpParticipant::tableName(), 'power', $this->double());
		$this->addColumn(\common\models\TmpParticipant::tableName(), 'isCruiser', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\TmpParticipant::tableName(), 'cbm');
		$this->dropColumn(\common\models\TmpParticipant::tableName(), 'power');
		$this->dropColumn(\common\models\TmpParticipant::tableName(), 'isCruiser');
	}
}
