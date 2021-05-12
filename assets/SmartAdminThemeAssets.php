<?php
declare(strict_types=1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class SmartAdminThemeWidgetAssets
 * @package app\widgets
 */
class SmartAdminThemeAssets extends AssetBundle
{
	public function init(): void
	{
		$this->depends = [YiiAsset::class];
		$this->sourcePath = __DIR__ . '/assets/theme';
		$this->css = [
			'css/vendors.bundle.css',
			'css/app.bundle.css',
			'css/fa-solid.css',
			'css/skins/skin-master.css',
		];
		$this->js = [
			'js/vendors.bundle.js',
			'js/app.bundle.js'
		];
		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('assets.publishOptions.forceCopy', false)
		];

		parent::init();
	}
}