<?php
declare(strict_types = 1);

namespace app\assets;

use app\models\core\Options;
use yii\web\AssetBundle;

/**
 * Class SwaggerAsset
 * @package app\assets
 */
class SwaggerAsset extends AssetBundle
{
	/**
	 * @inheritDoc
	 */
	public function init(): void
	{
		$this->sourcePath     = __DIR__ . '/assets/swagger';
		$this->css            = [
			'css/swagger-ui.css'
		];
		$this->js             = [
			'js/swagger-ui-bundle.js',
			'js/swagger-ui-standalone-preset.js',
			'js/swagger-onload.js',
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];

		parent::init();
	}
}