<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class ModalHelperAsset
 */
class ModalHelperAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/modalHelper/';
//		$this->css = [
//			'css/modalHelper.css'
//		];
		$this->js = [
			'js/modalHelper.js'
		];
		parent::init();
	}
}