<?php
declare(strict_types = 1);

namespace app\modules\notifications;

use app\models\core\Options;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class NotificationsModuleAssets
 */
class NotificationsModuleAssets extends AssetBundle {

	/**
	 * @inheritDoc
	 */
	public function init():void {
		$this->depends = [YiiAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->js = [
			'js/post.js',
		];
		$this->publishOptions = [
			'forceCopy' => Options::getValue(Options::ASSETS_PUBLISHOPTIONS_FORCECOPY)
		];

		parent::init();
	}
}