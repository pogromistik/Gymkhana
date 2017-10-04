<?php

namespace admin\assets;


use yii\web\AssetBundle;

class SBAdminAsset extends AssetBundle
{
    public $sourcePath = '@bower/node_modules/sb-admin-2/dist';
    public $css = [
        'css/sb-admin-2.css',
    ];

    public $js = [
        'js/sb-admin-2.js',
    ];

    public $depends = [
        MetisMenuAsset::class,
        FontAwesomeAsset::class
    ];
}