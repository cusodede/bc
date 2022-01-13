<?php
declare(strict_types = 1);

namespace app\assets;

use app\components\Options;
use yii\web\AssetBundle;

/**
 * Class OptionsAsset
 * @package app\modules\targets
 */
class OptionsAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/options';
		$this->js = [
			'js/options.js'
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}