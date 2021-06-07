<?php
declare(strict_types = 1);

namespace app\modules\status;

use pozitronik\core\helpers\ModuleHelper;
use pozitronik\core\traits\ModuleExtended;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 * Class ActiveHintsModule
 */
class ActiveHintsModule extends Module {
	use ModuleExtended;

	public const DEFAULT_CONFIG = [
		'tableName' => 'sys_active_hints'
	];

	/**
	 * @param string $name
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public static function getConfigParameter(string $name) {
		return ArrayHelper::getValue(ModuleHelper::params(self::class), $name, ArrayHelper::getValue(self::DEFAULT_CONFIG, $name));
	}

}