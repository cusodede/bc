<?php
declare(strict_types = 1);

namespace app\models\reward\config;

use app\models\core\prototypes\ArrayReference;

/**
 * Class RewardsOperationsConfig
 */
class RewardsOperationsConfig extends ArrayReference {
	public const OPERATION_SELL = 1;
	public const ACHIEVEMENT = 2;

	public array $items = [
		self::OPERATION_SELL => [
			'name' => 'Продажа товара'
		],
		self::ACHIEVEMENT => [
			'name' => 'Выполнение задания'
		]
	];

}