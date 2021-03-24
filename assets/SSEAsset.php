<?php
declare(strict_types = 1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
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
			'forceCopy' => SysOptions::getStatic('assets.publishOptions.forceCopy', false)
		];
		parent::init();
	}

}
