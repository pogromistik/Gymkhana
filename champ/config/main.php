<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id'                  => 'app-champ',
	'language'            => 'ru_RU',
	'timeZone'            => 'Asia/Yekaterinburg',
	'name'                => 'gymkhana74-champ',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'app\controllers',
	'bootstrap'           => ['log'],
	'modules'             => [
	],
	'components'          => [
		'request'      => [
			'csrfParam' => '_csrf-champ',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-champ', 'httpOnly' => true],
		],
		'session'      => [
			// this is the name of the session cookie used for login on the admin
			'name' => 'advanced-champ',
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				'<controller>/<action>'                  => '<controller>/<action>',
				'<controller:\w+>/<id:\d+>'              => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
			]
		],
		'authManager'  => [
			'class' => 'yii\rbac\DbManager',
		],
	],
	'params'              => $params,
];