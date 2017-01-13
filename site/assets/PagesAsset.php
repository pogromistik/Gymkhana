<?php

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Main site application asset bundle.
 */
class PagesAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/page/styles.css',
		'css/page/responsive.css',
	];
	public $js = [
		'js/scrollspy.js',
		'js/page.js'
	];
	public $depends = [
		AppAsset::class
	];
}
