<?php

namespace champ\assets;

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
	    'css/styles.css',
	    'css/responsive.css',
	    'css/font-awesome.min.css'
    ];
    public $js = [
	    'js/smoothscroll.min.js',
	    'js/bootstrap.min.js',
	    'js/main.js',
	    'js/jquery-ui.js',
	    'js/cabinet.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
