<?php
declare(strict_types = 1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
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
		$this->js = [
			'js/modalHelper.js'
		];
		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('assets.publishOptions.forceCopy', false)
		];
		parent::init();

	}
}