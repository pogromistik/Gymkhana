<?php

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Main site application asset bundle.
 */
class MainPageAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/main.css',
		'css/styles.css',
		'css/open-menu.css',
		'css/responsive.css'
	];
	public $js = [
		'js/scrollspymain.js',
		'js/main.js'
	];
	public $depends = [
		AppAsset::class
	];
}
