<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\components\helpers\Utils;
use app\components\subscription\exceptions\ResourceUnavailableException;
use app\modules\api\connectors\common\CommonSubscriptionParams;
use app\modules\api\connectors\vet_expert\VetExpertConnector;

/**
 * Class VetExpertSubscriptionHandler
 * @package app\components\subscription
 */
class VetExpertSubscriptionHandler extends BaseSubscriptionHandler
{
	private VetExpertConnector $_apiConnector;

	/**
	 * VetExpertSubscriptionHandler constructor.
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		parent::__construct($config);

		$this->_apiConnector = new VetExpertConnector();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function connectOnPartner(): string
	{
		$subscriptionParams = CommonSubscriptionParams::createInstance($this->_ticket->relatedAbonent);

		$expireDate                     = $this->_ticket->relatedSucceedBilling->calculateNewPaymentDate();
		$subscriptionParams->expireDate = $expireDate;

		$this->_apiConnector->processSubscription($subscriptionParams);

		return $expireDate;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function serviceCheck(): void
	{
		if (!Utils::doUrlHealthCheck($this->_apiConnector->baseUrl)) {
			throw new ResourceUnavailableException();
		}
	}
}