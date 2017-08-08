<?php

use yii\db\Migration;

class m170808_044231_add_videoLink_to_Times extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Time::tableName(), 'videoLink', $this->string());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Time::tableName(), 'videoLink');
	}
}
