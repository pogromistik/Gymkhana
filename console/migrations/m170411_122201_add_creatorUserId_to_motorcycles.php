<?php

use yii\db\Migration;

class m170411_122201_add_creatorUserId_to_motorcycles extends Migration
{
	public function up()
	{
		$this->addColumn(\common\models\Motorcycle::tableName(), 'creatorUserId', $this->integer());
	}
	
	public function down()
	{
		$this->dropColumn(\common\models\Motorcycle::tableName(), 'creatorUserId');
	}
}
