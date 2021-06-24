<?php
declare(strict_types = 1);

namespace app\modules\api\components\queue\job;

use app\components\subscription\SubscriptionHandler;
use app\models\abonents\Abonents;
use app\models\products\Products;
use DomainException;
use yii\queue\RetryableJobInterface;

/**
 * Class UnsubscribeProductJob
 * @package app\modules\api\components\queue\job
 */
class UnsubscribeProductJob implements RetryableJobInterface
{
	private int $_productId;
	private int $_abonentId;

	/**
	 * UnsubscribeProductJob constructor.
	 * @param int $productId
	 * @param int $abonentId
	 */
	public function __construct(int $productId, int $abonentId)
	{
		$this->_productId = $productId;
		$this->_abonentId = $abonentId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute($queue): void
	{
		$product = Products::findOne($this->_productId);
		$abonent = Abonents::findOne($this->_abonentId);
		if ((null === $product) || (null === $abonent)) {
			throw new DomainException('', 404);
		}

		$service = SubscriptionHandler::createInstanceByProduct($product);

		$service->revoke($this->_abonentId);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTtr(): int
	{
		return 300;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canRetry($attempt, $error): bool
	{
		return !$error instanceof DomainException || 404 !== $error->getCode();
	}
}