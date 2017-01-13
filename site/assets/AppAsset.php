<?php

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Main site application asset bundle.
 */
class AppAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/font-awesome.min.css',
		'css/owl.carousel.css',
		'css/owl.theme.css'
	];
	public $js = [
		'js/jquery.sliphover.min.js',
		'js/jquery.stellar.min.js',
		'js/owl.carousel.min.js',
		'js/smoothscroll.min.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
