<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Class ActiveHintsAssets
 */
class ActiveHintsAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/active_hints.css'];
		$this->depends = [
			AppAsset::class
		];
		parent::init();
	}
}








