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
	'controllerNamespace' => 'champ\controllers',
	'bootstrap'           => ['log'],
	'modules'             => [
	],
	'components'          => [
		'geoip' => ['class' => lysenkobv\GeoIP\GeoIP::class],
		'request'      => [
			'csrfParam' => '_csrf-champ',
		],
		'user'         => [
			'identityClass'   => 'common\models\Athlete',
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
				'<action>'=>'site/<action>',
				'user/login'=>'site/login',
			]
		],
		'assetManager' => [
			'appendTimestamp' => true,
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
