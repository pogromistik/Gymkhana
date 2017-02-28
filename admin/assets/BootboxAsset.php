<?php
namespace admin\assets;

use Yii;
use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
	public $sourcePath = '@vendor/bower/bootbox';
	public $js = [
		'bootbox.js',
	];
}