<?php

namespace admin\assets;


use yii\web\AssetBundle;

class MetisMenuAsset extends AssetBundle
{
    public $sourcePath = '@bower/node_modules/metismenu/dist';
    public $css = [
        'metisMenu.min.css',
    ];

    public $js = [
        'metisMenu.min.js',
    ];
}