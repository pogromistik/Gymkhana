<?php

use yii\db\Migration;

class m171031_042326_add_registrationFromSite extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Stage::tableName(), 'registrationFromSite', $this->integer()->notNull()->defaultValue(1));
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Stage::tableName(), 'registrationFromSite');
	}
}
