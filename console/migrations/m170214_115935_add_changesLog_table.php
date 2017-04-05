<?php

use console\components\PGMigration;

class m170214_115935_add_changesLog_table extends PGMigration
{
    public function safeUp()
    {
	    $this->createTable('ChangesLog', [
		    'id'         => self::primaryKey(),
		    'userId'     => self::integer()->notNull(),
		    'modelClass' => self::string()->notNull(),
		    'modelId'    => self::string()->notNull(),
		    'action'     => self::smallInteger()->notNull(),
		    'changes'    => self::jsonb(),
		    'comment'    => self::string()
	    ]);
	    $this->addForeignKey('ChangesLog_userId', 'ChangesLog', 'userId', 'user', 'id', 'NO ACTION', 'CASCADE');
	    $this->addColumn('ChangesLog', 'date', self::integer()->notNull()->defaultValue(0));
	    $this->createIndex('userId_indx', 'ChangesLog', 'userId');
	    $this->createIndex('date_indx', 'ChangesLog', 'date');
    }

    public function safeDown()
    {
	    $this->dropForeignKey('ChangesLog_userId', 'ChangesLog');
	    $this->dropTable('ChangesLog');
    }
}
