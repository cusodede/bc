<?php
declare(strict_types = 1);

namespace app\assets;

use app\models\core\Options;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class LoginAssetAsset
 * @package app\assets
 */
class LoginAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/login/';
		$this->css = [
			'css/login.css'
		];

		$this->depends = [
			AppAsset::class,
			YiiAsset::class,
			SmartAdminThemeAssets::class
		];

		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}
