<?php
declare(strict_types = 1);

namespace app\modules\export\models;

use app\modules\export\models\active_record\SysExportAR;
use pozitronik\filestorage\traits\FileStorageTrait;

/**
 * Class SysExport
 */
class SysExport extends SysExportAR {
	use FileStorageTrait;
}