<?php

namespace console\components;

use common\components\pg\PGSchema;
use yii\db\ColumnSchemaBuilder;
use yii\db\Migration;

class PGMigration extends Migration
{
	public function dropIndex($name, $table)
	{
		$this->db->createCommand($sql = 'DROP INDEX IF EXISTS ' . $this->db->quoteTableName($name))->execute();
	}
	
	public function dropForeignKey($name, $table)
	{
		$sql = 'select 1 from pg_constraint where conname = \'' . $name . '\'';
		$result = $this->db->createCommand($sql)->queryAll();
		if ($result) {
			parent::dropForeignKey($name, $table);
		}
	}
	
	/**
	 * Creates a primary key column.
	 * This parameter will be ignored if not supported by the DBMS.
	 * @return ColumnSchemaBuilder the column instance which can be further customized.
	 * @since 2.0.6
	 */
	public function jsonb()
	{
		return $this->getDb()->getSchema()->createColumnSchemaBuilder(PGSchema::TYPE_JSONB);
	}
}
