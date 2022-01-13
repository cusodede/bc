<?php
declare(strict_types = 1);

namespace app\assets;

use app\components\Options;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class ValidationAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/validation/';
		$this->js = [
			'js/validation.js'
		];

		$this->depends = [
			AppAsset::class,
		];

		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}

}
