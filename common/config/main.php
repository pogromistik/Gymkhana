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
				'username'   => 'support@gymkhana-cup.ru',
				'password'   => 'P1l0N6v4',
				'port'       => '587',
				'encryption' => 'tls',
			],
		],
	]
];
