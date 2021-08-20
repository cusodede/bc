<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\components\helpers\Utils;
use app\components\subscription\exceptions\ResourceUnavailableException;
use app\components\subscription\exceptions\SubscriptionUnavailableException;
use app\modules\api\connectors\ivi\IviConnector;
use app\modules\api\connectors\ivi\ProductOptions;
use app\modules\api\connectors\ivi\PurchaseOptionsHandler;
use app\modules\api\connectors\ivi\PurchaseOptionsItem;
use DomainException;
use Throwable;
use yii\httpclient\Exception;

/**
 * Class IviSubscriptionHandler
 * @package app\components\subscription
 */
class IviSubscriptionHandler extends BaseSubscriptionHandler
{
	/**
	 * @var IviConnector
	 */
	private IviConnector $_apiConnector;
	/**
	 * @var ProductOptions|null
	 */
	private ?ProductOptions $_productOptions = null;

	/**
	 * IviSubscriptionHandler constructor.
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		parent::__construct($config);

		$this->_apiConnector = new IviConnector();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function beforeSubscribe(): void
	{
		parent::beforeSubscribe();

		$this->initProductOptions();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function connectOnPartner(): string
	{
		$purchaseId = $this->makePurchase();

		$this->_ticket->log(['purchaseId' => $purchaseId]);

		//делаем повторный запрос на получение актуальной информации по подключенной подписке
		$purchaseOptionsHandler = $this->callPurchaseOptions();

		$purchaseData = $purchaseOptionsHandler->getPurchasesCollection()->extractById($purchaseId);
		if (null === $purchaseData) {
			throw new DomainException("Не удалось определить подписку по ID $purchaseId");
		}

		return $purchaseData->getExpireDate();
	}

	/**
	 * @return int ID покупки.
	 * @throws Exception
	 * @throws Throwable
	 */
	private function makePurchase(): int
	{
		if (null === $productPurchaseOptions = $this->getProductPurchaseOptions()) {
			throw new SubscriptionUnavailableException("Среди опций покупки отсутствует продукт с идентификатором {$this->_productOptions->productId}");
		}

		$result = $this->_apiConnector->makeSubscribe($this->_productOptions, $productPurchaseOptions);

		return $result->getPurchaseId();
	}

	/**
	 * @return PurchaseOptionsItem|null
	 * @throws Exception
	 * @throws Throwable
	 */
	private function getProductPurchaseOptions(): ?PurchaseOptionsItem
	{
		$purchaseOptionsHandler = $this->callPurchaseOptions();

		return $purchaseOptionsHandler->getPurchasesOptionsCollection()->extractProductPurchaseOptions($this->_productOptions->productId);
	}

	/**
	 * @return PurchaseOptionsHandler
	 * @throws Exception
	 * @throws Throwable
	 */
	private function callPurchaseOptions(): PurchaseOptionsHandler
	{
		return $this->_apiConnector->getPurchaseOptions($this->_productOptions);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function serviceCheck(): void
	{
		$status = Utils::doUrlHealthCheck($this->_apiConnector->baseUrl);
		if (!$status) {
			throw new ResourceUnavailableException();
		}

		$this->initProductOptions();
		if (null === $this->getProductPurchaseOptions()) {
			throw new SubscriptionUnavailableException("Среди опций покупки отсутствует продукт с идентификатором {$this->_productOptions->productId}");
		}
	}

	private function initProductOptions(): void
	{
		$this->_productOptions = new ProductOptions([
			'productId'     => $this->_ticket->relatedProduct->id,
			'phone'         => $this->_ticket->relatedAbonent->phone,
			'transactionId' => $this->_ticket->id
		]);
	}
}