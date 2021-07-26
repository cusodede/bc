<?php
declare(strict_types = 1);

namespace app\components\subscription\job;

use app\components\subscription\BaseSubscriptionHandler;
use app\models\products\Products;
use yii\queue\RetryableJobInterface;
use yii\web\NotFoundHttpException;

/**
 * Class UnsubscribeJob
 * @package app\components\subscription\job
 */
class UnsubscribeJob implements RetryableJobInterface
{
	private int $_productId;
	private int $_abonentId;

	/**
	 * UnsubscribeJob constructor.
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
		if (null === $product) {
			throw new NotFoundHttpException();
		}

		$service = BaseSubscriptionHandler::createInstanceByProduct($product);

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
		return true;
	}
}