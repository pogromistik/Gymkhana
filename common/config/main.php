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
		'mailer' => [
			'class'     => 'yii\swiftmailer\Mailer',
			'transport' => [
				'class'      => 'Swift_SmtpTransport',
				'host'       => 'localhost',
				'username'   => 'support@mg-cup.ru',
				'password'   => 'A8p3X7j0',
				'port'       => '587',
				'encryption' => 'tls',
			],
		],
	]
];
