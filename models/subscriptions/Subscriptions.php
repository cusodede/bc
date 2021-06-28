<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use app\models\core\prototypes\ActiveRecordTrait;
use app\models\subscriptions\active_record\Subscriptions as ActiveRecordSubscriptions;
use yii\helpers\ArrayHelper;

/**
 * Логика подписок, не относящиеся к ActiveRecord
 * Class Subscriptions
 * @package app\models\subscriptions
 */
class Subscriptions extends ActiveRecordSubscriptions
{
	use ActiveRecordTrait;

	public const SCENARIO_CREATE_AJAX = 'create_ajax'; // Сценарий создания вместе с партнером

	/**
	 * @return array
	 */
	public function scenarios(): array
	{
		return ArrayHelper::merge(parent::scenarios(), [
			// Валидация только нужных атрибутов, при создании/обновлении ajax
			self::SCENARIO_CREATE_AJAX => ['trial_count', 'units'],
		]);
	}
}