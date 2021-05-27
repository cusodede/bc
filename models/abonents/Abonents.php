<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;

/**
 * Class Abonents
 * @package app\models\abonents
 */
class Abonents extends ActiveRecordAbonents
{
	use ActiveRecordTrait;
}