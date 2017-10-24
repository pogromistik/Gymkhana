<?php

namespace common\components\pg;


use yii\db\pgsql\Schema;

class PGSchema extends Schema
{
	const TYPE_JSONB = 'jsonb';
	const NOT_NULL = ' NOT NULL';
	
	public function createColumnSchemaBuilder($type, $length = null)
	{
		return new PGColumnSchemaBuilder($type, $length);
	}
	
}