<?php
declare(strict_types = 1);

namespace app\assets;

use app\models\core\Options;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 */
class SSEAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/sse/';
		$this->js = [
			'js/sseListener.js'
		];

		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}

}
