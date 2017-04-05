<?php

namespace common\components\pg;


use yii\db\ColumnSchemaBuilder;
use yii\db\pgsql\Schema;

class PGColumnSchemaBuilder extends ColumnSchemaBuilder
{
	public $categoryMap = [
		Schema::TYPE_PK        => self::CATEGORY_PK,
		Schema::TYPE_UPK       => self::CATEGORY_PK,
		Schema::TYPE_BIGPK     => self::CATEGORY_PK,
		Schema::TYPE_UBIGPK    => self::CATEGORY_PK,
		Schema::TYPE_CHAR      => self::CATEGORY_STRING,
		Schema::TYPE_STRING    => self::CATEGORY_STRING,
		Schema::TYPE_TEXT      => self::CATEGORY_STRING,
		Schema::TYPE_SMALLINT  => self::CATEGORY_NUMERIC,
		Schema::TYPE_INTEGER   => self::CATEGORY_NUMERIC,
		Schema::TYPE_BIGINT    => self::CATEGORY_NUMERIC,
		Schema::TYPE_FLOAT     => self::CATEGORY_NUMERIC,
		Schema::TYPE_DOUBLE    => self::CATEGORY_NUMERIC,
		Schema::TYPE_DECIMAL   => self::CATEGORY_NUMERIC,
		Schema::TYPE_DATETIME  => self::CATEGORY_TIME,
		Schema::TYPE_TIMESTAMP => self::CATEGORY_TIME,
		Schema::TYPE_TIME      => self::CATEGORY_TIME,
		Schema::TYPE_DATE      => self::CATEGORY_TIME,
		Schema::TYPE_BINARY    => self::CATEGORY_OTHER,
		Schema::TYPE_BOOLEAN   => self::CATEGORY_NUMERIC,
		Schema::TYPE_MONEY     => self::CATEGORY_NUMERIC,
		PGSchema::TYPE_JSONB   => self::CATEGORY_STRING
	];
}