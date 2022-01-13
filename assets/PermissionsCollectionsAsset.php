<?php
declare(strict_types = 1);

namespace app\assets;

use app\components\Options;
use yii\web\AssetBundle;

/**
 * Class PermissionsCollectionsAsset
 * @package app\assets
 */
class PermissionsCollectionsAsset extends AssetBundle {

	/**
	 * {@inheritDoc}
	 */
	public function init():void {
		$this->sourcePath = __DIR__.'/assets/permissionsCollections/';
		$this->js = [
			'js/permissionsCollections.js'
		];

		$this->depends = [
			AppAsset::class,
		];

		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];
		parent::init();
	}
}
