<?php
declare(strict_types = 1);

namespace app\widgets\selectmodelwidget;

use app\models\core\Options;
use yii\web\AssetBundle;

/**
 * Class SelectModelWidgetAssets
 */
class SelectModelWidgetAssets extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public function init(): void
	{
		$this->sourcePath = __DIR__ . '/assets';
//		$this->css = ['css/select_model.css'];
		$this->js             = [
			'js/select_model.js'
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}