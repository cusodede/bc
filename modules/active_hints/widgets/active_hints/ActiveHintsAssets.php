<?php
declare(strict_types = 1);

namespace app\modules\active_hints\widgets\active_hints;

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
//		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








