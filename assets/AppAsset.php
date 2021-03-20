<?php
declare(strict_types = 1);
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Main application asset bundle.
 */
class AppAsset extends AssetBundle {
	public $sourcePath = __DIR__.'/assets/app/';
	public $css = [
		'css/site.css',
	];
	public $depends = [
		YiiAsset::class,
		BootstrapAsset::class,
	];
}
