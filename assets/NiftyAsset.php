<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Class LoginAssetAsset
 * @package app\assets
 */
class NiftyAsset extends AssetBundle {
	public $sourcePath = __DIR__.'/assets/nifty/';
	public $css = [
		'css/nifty.min.css'
	];
	public $js = [
		'js/nifty.min.js'
	];

	public $depends = [
		AppAsset::class,
		YiiAsset::class,
		BootstrapAsset::class,
	];
}
