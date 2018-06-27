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
	    'css/font-awesome.min.css',
	    'css/owl.carousel.css',
	    'css/owl.theme.css',
	    'plugins/fancybox/dist/jquery.fancybox.min.css',
    ];
    public $js = [
	    'plugins/fancybox/dist/jquery.fancybox.min.js',
	    /*'js/smoothscroll.min.js',*/
	    'js/owl.carousel.min.js',
	    'js/site.js',
	    'js/jquery-ui.js',
	    'js/cabinet.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
	    BootstrapPluginAsset::class,
    ];
}
