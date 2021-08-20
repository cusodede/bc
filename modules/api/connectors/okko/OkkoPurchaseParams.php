<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\okko;

use app\common\Arrayable;
use yii\base\BaseObject;

/**
 * Class OkkoPurchaseParams
 * @package app\modules\api\connectors\okko
 */
class OkkoPurchaseParams extends BaseObject implements Arrayable
{
	public const ACTION_NEW = 'NEW';
	public const ACTION_PROLONG = 'PROLONG';

	/**
	 * @var string Тип операции: NEW - подключение, PROLONG - продление, EXIT - отключение.
	 */
	private string $_action = self::ACTION_NEW;
	/**
	 * @var string Дата начала действия подписки.
	 */
	private string $_dateStart = '';
	/**
	 * @var string Номер телефона пользователя.
	 */
	private string $_phone = '';
	/**
	 * @var string Идентификатор покупки.
	 */
	private string $_purchaseId = '';
	/**
	 * @var string Идентификатор подписки.
	 */
	private string $_serviceId = '';
	/**
	 * @var bool Признак бесплатного пробного периода.
	 */
	private bool $_trial = false;

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return [
			'action'     => $this->_action,
			'dtStart'    => $this->_dateStart,
			'msisdn'     => $this->_phone,
			'purchaseId' => $this->_purchaseId,
			'serviceId'  => $this->_serviceId,
			'trial'      => $this->_trial ? 'true' : 'false'
		];
	}
}