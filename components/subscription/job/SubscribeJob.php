<?php
declare(strict_types = 1);

namespace app\components\subscription\job;

use app\components\subscription\BaseSubscriptionHandler;
use app\models\products\Products;
use yii\queue\RetryableJobInterface;
use yii\web\NotFoundHttpException;

/**
 * Class SubscribeJob
 * @package app\components\subscription\job
 */
class SubscribeJob implements RetryableJobInterface
{
	private int $_productId;
	private int $_abonentId;

	/**
	 * SubscribeJob constructor.
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

		$service->provide($this->_abonentId, '');//TODO add billing operation stuff
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