<?php
declare(strict_types = 1);

namespace app\models\subscriptions;

use app\models\subscriptions\active_record\Subscriptions as ActiveRecordSubscriptions;
use yii\helpers\ArrayHelper;
use Exception;

/**
 * Логика подписок, не относящиеся к ActiveRecord
 * Class Subscriptions
 * @package app\models\subscriptions
 *
 * @property-read string $unitName
 */
class Subscriptions extends ActiveRecordSubscriptions
{
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

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getUnitName(): string
	{
		return EnumSubscriptionTrialUnits::getScalar($this->units);
	}

	/**
	 * @param string $date
	 * @return string
	 */
	public function calculatePromoPeriodEndDate(string $date): string
	{
		if (0 === $this->trial_count) {
			return $date;
		}

		$modifier = "+ $this->trial_count " .  match ($this->units) {
			EnumSubscriptionTrialUnits::UNIT_DAYS => 'days',
			EnumSubscriptionTrialUnits::UNIT_WEEK => 'weeks',
			default => 'months',
		};

		return date_create($date)->modify($modifier)->format('Y-m-d H:i:s');
	}
}