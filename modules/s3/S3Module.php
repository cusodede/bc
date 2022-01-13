<?php
declare(strict_types = 1);

namespace app\modules\s3;

use pozitronik\traits\traits\ModuleTrait;
use Yii;
use Exception;
use yii\base\Module as YiiModule;
use yii\console\Application;

/**
 * Class S3Module
 * @package app\modules\s3
 */
class S3Module extends YiiModule {
	use ModuleTrait;

	/**
	 * @inheritDoc
	 */
	public function init():void {
		parent::init();

		try {
			if (Yii::$app instanceof Application) {
				$this->controllerNamespace = 'app\modules\s3\commands';
			}
		} catch (Exception $e) {
			Yii::error($e->getTraceAsString(), 'recogdol.api');
		}
	}
}
