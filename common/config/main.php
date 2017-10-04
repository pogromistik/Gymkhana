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
	]
];
