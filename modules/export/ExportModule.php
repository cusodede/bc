<?php
declare(strict_types = 1);

namespace app\modules\export;

use pozitronik\traits\traits\ModuleTrait;
use yii\base\Module;

/**
 * Class ExportModule
 * Модуль для экспорта разных XLS, CSV и т.д.
 * см. docs/README.md
 */
class ExportModule extends Module {
	use ModuleTrait;
}