<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\subscriptions\active_record\Subscriptions as ActiveRecordSubscriptions;

/**
 * Логика подписок, не относящиеся к ActiveRecord
 * Class Subscriptions
 * @package app\models\subscriptions
 */
class Subscriptions extends ActiveRecordSubscriptions
{
	use ActiveRecordTrait;
}