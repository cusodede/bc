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
			// Валидируем только нужные атрибуты при создании/обновлении ajax
			self::SCENARIO_CREATE_AJAX => ['category_id', 'trial', 'trial_days_count'],
		]);
	}
}