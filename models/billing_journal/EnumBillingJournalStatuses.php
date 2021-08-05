<?php
declare(strict_types = 1);

namespace app\models\billing_journal;

use app\components\helpers\Html;
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

	/**
	 * @param int $statusId
	 * @return string
	 */
	public static function getBadge(int $statusId): string
	{
		$statusDesc = self::mapData()[$statusId];

		if (self::STATUS_CHARGED === $statusId) {
			return Html::badgeSuccess($statusDesc);
		}
		if (self::STATUS_FAILURE === $statusId) {
			return Html::badgeError($statusDesc);
		}
		return Html::badgeInfo($statusDesc);
	}
}