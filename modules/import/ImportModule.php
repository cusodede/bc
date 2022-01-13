<?php
declare(strict_types = 1);

namespace app\modules\import;

use pozitronik\traits\traits\ModuleTrait;
use yii\base\Module;

/**
 * Class ImportModule
 * Модуль для импорта разных XLS, CSV и т.д.
 * см. docs/README.md
 */
class ImportModule extends Module {
	use ModuleTrait;
}