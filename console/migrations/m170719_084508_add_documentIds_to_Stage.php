<?php

use console\components\PGMigration;

class m170719_084508_add_documentIds_to_Stage extends PGMigration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Stage::tableName(), 'documentIds', $this->jsonb());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Stage::tableName(), 'documentIds');
	}
}
