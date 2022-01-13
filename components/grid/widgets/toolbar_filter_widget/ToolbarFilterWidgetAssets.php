<?php
declare(strict_types = 1);

namespace app\components\grid\widgets\toolbar_filter_widget;

use app\components\Options;
use yii\web\AssetBundle;

/**
 * Class ToolbarFilterWidgetAssets
 */
class ToolbarFilterWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets';
		$this->css = [
			'css/toolbar_widget.css'
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}