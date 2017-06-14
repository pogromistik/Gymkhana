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
		'css/cup.css',
		'css/font-awesome.min.css'
	];
	public $js = [
		'js/main.js',
		'js/cup.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		BootstrapPluginAsset::class,
		SBAdminAsset::class,
		'admin\assets\BootboxAsset',
	];
}
