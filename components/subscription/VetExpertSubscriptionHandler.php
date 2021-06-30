<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\products\Products;
use app\modules\api\connectors\vet_expert\SubscriptionParams;
use app\modules\api\connectors\vet_expert\VetExpertConnector;

/**
 * Class VetExpertSubscriptionHandler
 * @package app\components\subscription
 */
class VetExpertSubscriptionHandler extends SubscriptionHandler
{
	private VetExpertConnector $_apiConnector;

	/**
	 * VetExpertSubscriptionHandler constructor.
	 * @param Products $product
	 * @param array $config
	 */
	public function __construct(Products $product, $config = [])
	{
		parent::__construct($product, $config);

		$this->_apiConnector = new VetExpertConnector();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function subscribe(): string
	{
		$subscriptionParams = SubscriptionParams::createInstance($this->_abonent);

		$expireDate = $this->_billingJournalRecord->calculateNewPaymentDate();
		$subscriptionParams->subscriptionTo = $expireDate;

		$this->_apiConnector->makeSubscribe($subscriptionParams);

		return $expireDate;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function unsubscribe(): void
	{
		//ничего не отправляем партнеру, подписка просто протухнет, если мы ее принудительно не обновим
	}
}