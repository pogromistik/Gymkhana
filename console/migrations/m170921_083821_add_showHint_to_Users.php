<?php

use yii\db\Migration;

class m170921_083821_add_showHint_to_Users extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\User::tableName(), 'showHint', $this->integer()->null()->defaultValue(1));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\User::tableName(), 'showHint');
	}
}
