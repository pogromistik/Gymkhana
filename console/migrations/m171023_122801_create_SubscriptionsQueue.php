<?php

use yii\db\Migration;

class m171023_122801_create_SubscriptionsQueue extends Migration
{
	public function safeUp()
	{
		$this->createTable('SubscriptionsQueue', [
			'id'          => $this->primaryKey(),
			'countEmails' => $this->integer(),
			'type'        => $this->integer()->notNull(),
			'messageType' => $this->integer()->notNull(),
			'modelId'     => $this->integer()->notNull(),
			'dateAdded'   => $this->integer()->notNull(),
			'dateSend'    => $this->integer(),
			'isActual'    => $this->integer()->notNull()->defaultValue(1)
		]);
	}
	
	public function safeDown()
	{
		$this->dropTable('SubscriptionsQueue');
	}
}
