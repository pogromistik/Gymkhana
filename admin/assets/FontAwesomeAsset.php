<?php

namespace admin\assets;


use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $sourcePath = '@node_modules/font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];
}