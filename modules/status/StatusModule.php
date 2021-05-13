<?php
declare(strict_types = 1);

namespace app\modules\status;

use pozitronik\core\traits\ModuleExtended;
use pozitronik\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\Module;

/**
 * Class StatusModule
 * @package status
 */
class StatusModule extends Module {
	use ModuleExtended;

	/**
	 * @param string $className
	 * @return array
	 * @throws Throwable
	 */
	public static function getClassRules(string $className):array {
		return ArrayHelper::getValue(Yii::$app->modules, "statuses.params.rules.$className", []);
	}
}