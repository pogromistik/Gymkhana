<?php

namespace admin\assets;

use yii\web\AssetBundle;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * Main admin application asset bundle.
 */
class AppAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'dev/css/admin.css',
		'dev/css/font-awesome.min.css'
	];
	public $js = [
		'dev/js/main.js',
		'dev/js/competitions.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		BootstrapPluginAsset::class,
		SBAdminAsset::class,
		'admin\assets\BootboxAsset',
	];
}
