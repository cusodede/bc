<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

use app\assets\AppAsset;
use pozitronik\sys_options\models\SysOptions;
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
//		$this->js = ['js/active_hints.js'];
		$this->depends = [
			AppAsset::class
		];

		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('ASSETS_PUBLISHOPTIONS_FORCECOPY', false)
		];
		parent::init();
	}
}








