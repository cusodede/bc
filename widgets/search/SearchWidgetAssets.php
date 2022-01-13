<?php
declare(strict_types = 1);

namespace app\widgets\search;

use app\components\Options;
use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class SearchWidgetAssets
 * @package app\components\search
 */
class SearchWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init():void {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/search.css'];
//		$this->js = ['js/search.js'];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}