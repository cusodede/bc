<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

use Exception;
use yii\helpers\ArrayHelper;

/**
 * Class PurchaseItem
 * @package app\modules\api\connectors\ivi
 */
class PurchaseItem
{
	private array $_data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->_data = $data;
	}

	/**
	 * @return int|null
	 * @throws Exception
	 */
	public function getId(): ?int
	{
		return ArrayHelper::getValue($this->_data, 'id');
	}

	/**
	 * Получение даты истечения услуги.
	 * @return string|null
	 * @throws Exception
	 */
	public function getExpireDate(): ?string
	{
		return ArrayHelper::getValue($this->_data, 'finish_time');
	}
}