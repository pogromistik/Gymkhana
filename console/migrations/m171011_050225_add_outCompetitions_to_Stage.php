<?php

use yii\db\Migration;

class m171011_050225_add_outCompetitions_to_Stage extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Stage::tableName(), 'outOfCompetitions', $this->integer()->notNull()->defaultValue(0));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Stage::tableName(), 'outOfCompetitions');
	}
}
