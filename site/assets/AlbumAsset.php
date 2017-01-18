<?php

namespace site\assets;

use yii\web\AssetBundle;

/**
 * Main site application asset bundle.
 */
class AlbumAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'fotorama/fotorama.css'
	];
	public $js = [
		'fotorama/fotorama.js'
	];
	public $depends = [
		PagesAsset::class
	];
}
