<?php
declare(strict_types = 1);

namespace app\assets;

use app\components\Options;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class SmartAdminThemeWidgetAssets
 */
class SmartAdminThemeAssets extends AssetBundle {

	/**
	 * @inheritDoc
	 */
	public function init():void {
		$this->depends = [YiiAsset::class];
		$this->sourcePath = __DIR__.'/assets/theme';
		$this->css = [
			'css/vendors.bundle.css',
			'css/app.bundle.css',
			'css/fa-solid.css',
			'css/skins/skin-master.css',
			'css/notifications/toastr/toastr.css'
		];
		$this->js = [
			'js/init.js',
			'js/vendors.bundle.js',
			'js/app.bundle.js',
			'js/app.config.js',
			'js/notifications/toastr/toastr.js',
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];

		parent::init();
	}
}