<?php

use yii\db\Migration;

class m170303_134008_add_regionId_to_Athletes extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Athlete::tableName(), 'regionId', $this->integer());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Athlete::tableName(), 'regionId');
	}
}
