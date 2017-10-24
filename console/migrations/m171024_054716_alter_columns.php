<?php
use console\components\PGMigration;

class m171024_054716_alter_columns extends PGMigration
{
	public function safeUp()
	{
		$this->alterColumn(\common\models\NewsSubscription::tableName(), 'types', $this->jsonb());
		$this->alterColumn(\common\models\NewsSubscription::tableName(), 'countryIds', $this->jsonb());
		$this->alterColumn(\common\models\NewsSubscription::tableName(), 'regionIds', $this->jsonb());
		$this->alterColumn(\common\models\ChangesLog::tableName(), 'changes', $this->jsonb());
		$this->alterColumn(\common\models\Championship::tableName(), 'onlyRegions', $this->jsonb());
		$this->alterColumn(\common\models\Stage::tableName(), 'documentIds', $this->jsonb());
		$this->alterColumn(\common\models\TmpAthlete::tableName(), 'motorcycles', $this->jsonb());
	}
	
	public function safeDown()
	{
		return true;
	}
	
	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m171024_054716_alter_columns cannot be reverted.\n";

		return false;
	}
	*/
}
