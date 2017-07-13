<?php
$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id'                  => 'app-admin',
	'language'            => 'ru_RU',
	'timeZone'            => 'Asia/Yekaterinburg',
	'name'                => 'GymkhanaCup',
	'basePath'            => dirname(__DIR__),
	'defaultRoute'        => 'help/index',
	'controllerNamespace' => 'admin\controllers',
	'bootstrap'           => ['log'],
	'modules'             => [
		'user' => [
			'class'              => \dektrium\user\Module::class,
			'enableRegistration' => false,
			'enableConfirmation' => false,
			'admins'             => ['nadia'],
			'controllerMap'      => [
				'security' => \admin\controllers\SecurityController::class
			],
			'modelMap'           => [
				'User' => \common\models\User::className()
			],
		],
		'rbac'     => [
			'class' => 'dektrium\rbac\Module',
		],
	],
	'components'          => [
		'request'      => [
			'csrfParam' => '_csrf-admin',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-admin', 'httpOnly' => true],
		],
		'session'      => [
			// this is the name of the session cookie used for login on the admin
			'name' => 'advanced-admin',
		],
		'assetManager' => [
			'appendTimestamp' => true,
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
		'view' => [
			'theme' => [
				'pathMap' => [
					'@dektrium/user/views' => '@admin/views/user'
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
		'i18n'         => [
			'translations' => [
				'app*' => [
					'class'              => 'yii\i18n\DbMessageSource',
					'messageTable'       => \common\models\TranslateMessage::tableName(),
					'sourceMessageTable' => \common\models\TranslateMessageSource::tableName(),
					'enableCaching'      => true,
					'cachingDuration'    => 10,
					'forceTranslation'   => true,
				],
			],
		],
	],
	'params'              => $params,
];
