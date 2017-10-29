<?php

use yii\db\Migration;

class m171024_090021_add_token_to_NewsSubscription extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\NewsSubscription::tableName(), 'token', $this->text());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\NewsSubscription::tableName(), 'token');
	}
}
