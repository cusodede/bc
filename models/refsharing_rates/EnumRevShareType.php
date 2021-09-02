<?php
declare(strict_types = 1);

namespace app\models\refsharing_rates;

use app\models\common\EnumTrait;

/**
 * Class EnumRevShareType
 * @package app\models\refsharing_rates
 */
class EnumRevShareType
{
	use EnumTrait;

	public const TYPE_NUMBER_OF_ATTRACTED_CLIENTS = 1;
	public const TYPE_PROFIT = 2;

	/**
	 * {@inheritdoc}
	 */
	public static function mapData(): array
	{
		return [
			self::TYPE_NUMBER_OF_ATTRACTED_CLIENTS => 'Кол-во привлеченных клиентов',
			self::TYPE_PROFIT => 'Прибыль',
		];
	}
}