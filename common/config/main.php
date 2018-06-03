<?php
return [
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'components' => [
		'cache'  => [
			'class' => 'yii\caching\FileCache',
		],
		'mutex'  => [
			'class' => \yii\mutex\FileMutex::class,
		],
		'db' => [
			'schemaMap'         => [
				'pgsql' => [
					'class'             => yii\db\pgsql\Schema::class,
					'columnSchemaClass' => [
						'class'                                   => \yii\db\pgsql\ColumnSchema::class,
						'disableJsonSupport'                      => true,
						'disableArraySupport'                     => true,
						'deserializeArrayColumnToArrayExpression' => false,
					],
				],
			]
		],
	]
];
