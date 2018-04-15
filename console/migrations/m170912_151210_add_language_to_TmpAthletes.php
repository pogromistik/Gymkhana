<?php

use yii\db\Migration;

class m170912_151210_add_language_to_TmpAthletes extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\TmpAthlete::tableName(), 'language', $this->string());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\TmpAthlete::tableName(), 'language');
	}
}
