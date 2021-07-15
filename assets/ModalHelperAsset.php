<?php
declare(strict_types = 1);

namespace app\assets;

use pozitronik\sys_options\models\SysOptions;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class ModalHelperAsset
 */
class ModalHelperAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->depends = [YiiAsset::class];
		$this->sourcePath = __DIR__.'/assets/modalHelper/';
		$this->js = [
			'js/modalHelper.js'
		];
		$this->css = [
			'css/modalHelper.css'
		];
		$this->publishOptions = [
			'forceCopy' => SysOptions::getStatic('ASSETS_PUBLISHOPTIONS_FORCECOPY', false)
		];
		parent::init();

	}
}