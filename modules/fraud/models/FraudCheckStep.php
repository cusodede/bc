<?php
declare(strict_types = 1);

namespace app\modules\fraud\models;

use app\modules\fraud\models\active_record\FraudCheckStepAr;

/**
 * Фродовая проверка
 *
 * Class FraudCheckStep
 * @package app\modules\fraud\models
 */
class FraudCheckStep extends FraudCheckStepAr {
	public const STATUS_SUCCESS = 1;
	public const STATUS_WAIT = 2;
	public const STATUS_PROCESS = 3;
	public const STATUS_FAIL = 4;

	public static array $statusesWithNames = [
		self::STATUS_SUCCESS => 'Фрод не найден',
		self::STATUS_WAIT => 'Ожидает проверки',
		self::STATUS_PROCESS => 'Обрабатывается',
		self::STATUS_FAIL => 'Фрод найден'
	];

	/**
	 * @param int $productOrderId
	 * @param string $entityClass
	 * @param string $fraudValidatorClass
	 * @return static
	 */
	public static function newStep(
		int $productOrderId,
		string $entityClass,
		string $fraudValidatorClass
	):self {
		$new = new self();
		$new->entity_id = $productOrderId;
		$new->entity_class = $entityClass;
		$new->fraud_validator = $fraudValidatorClass;
		$new->status = self::STATUS_WAIT;
		$new->created_at = $new->updated_at = date('Y-m-d H:i:s');
		return $new;
	}

	/**
	 * @return string|null
	 */
	public function getStatusName():?string
	{
		if ( ! array_key_exists($this->status, self::$statusesWithNames)) {
			return nulll;
		}
		return self::$statusesWithNames[$this->status];
	}
}