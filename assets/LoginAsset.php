<?php
declare(strict_types = 1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

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
			BootstrapAsset::class,
		];

		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('assets.publishOptions.forceCopy', false)
		];
		parent::init();
	}
}
