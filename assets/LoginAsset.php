<?php
declare(strict_types = 1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
use yii\web\AssetBundle;

/**
 * Class LoginAssetAsset
 * @package app\assets
 */
class LoginAsset extends AssetBundle
{
	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
		$this->sourcePath = __DIR__ . '/assets/login/';

		$this->depends = [
			SmartAdminThemeAssets::class
		];

		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('ASSETS_PUBLISHOPTIONS_FORCECOPY', false)
		];

		parent::init();
	}
}
