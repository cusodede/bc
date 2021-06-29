<?php
declare(strict_types = 1);

namespace app\models\billing_journal;

use app\models\common\EnumTrait;

/**
 * Class EnumBillingJournalStatuses
 * @package app\models\billing_journal
 */
class EnumBillingJournalStatuses
{
	use EnumTrait;

	public const STATUS_CHARGED = 1;
	public const STATUS_FAILURE = 2;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::STATUS_CHARGED => 'Средства списаны',
			self::STATUS_FAILURE => 'Ошибка списания'
		];
	}

}