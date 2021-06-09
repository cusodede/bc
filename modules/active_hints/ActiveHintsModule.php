<?php
declare(strict_types = 1);

namespace app\modules\active_hints;

use kartik\popover\PopoverX;
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

	/**
	 * Параметры `enabled` и `editable` можно задать замыканием вида
	 * function (IdentityInterface $identity):bool {
	 *
	 * }
	 */
	public const DEFAULT_CONFIG = [
		'tableName' => 'sys_active_hints',
		'defaultPlacement' => PopoverX::ALIGN_AUTO,
		'template' => "{label}{activeHint}\n{input}\n{error}",
		'enabled' => true,
		'editable' => false
	];

	/**
	 * @param string $name
	 * @return mixed
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function getConfigParameter(string $name) {
		return ArrayHelper::getValue(ModuleHelper::params(self::class), $name, ArrayHelper::getValue(self::DEFAULT_CONFIG, $name));
	}

}