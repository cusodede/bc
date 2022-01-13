<?php
declare(strict_types = 1);

namespace app\assets;

use app\components\Options;
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
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();

	}
}