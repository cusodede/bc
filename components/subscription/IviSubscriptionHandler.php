<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\products\Products;
use app\modules\api\connectors\ivi\IviConnector;
use app\modules\api\connectors\ivi\ProductOptions;
use app\modules\api\connectors\ivi\PurchaseOptionsHandler;
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
	 * @param Products $product
	 * @param array $config
	 */
	public function __construct(Products $product, $config = [])
	{
		parent::__construct($product, $config);

		$this->_apiConnector = new IviConnector();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function beforeSubscribe(): void
	{
		parent::beforeSubscribe();

		$this->_productOptions = new ProductOptions([
			'productId'     => $this->_product->id,
			'phone'         => $this->_abonent->phone,
			'transactionId' => $this->_billingJournalRecord->id
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function subscribe(): string
	{
		$purchaseId = $this->makePurchase();
		//TODO предусмотреть сохранение идентификатора продукта в системе

		//делаем повторный запрос на получение актуальной информации по подключенной подписке
		$purchaseOptionsHandler = $this->callPurchaseOptions();

		$purchaseData = $purchaseOptionsHandler->getPurchasesCollection()->extractById($purchaseId);
		if (null === $purchaseData) {
			throw new DomainException("Не удалось найти покупку с ID $purchaseId");
		}

		return $purchaseData->getExpireDate();
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
	 * @return int ID покупки.
	 * @throws Exception
	 * @throws Throwable
	 */
	private function makePurchase(): int
	{
		$purchaseOptionsHandler = $this->callPurchaseOptions();

		$productPurchaseOptions = $purchaseOptionsHandler
			->getPurchasesOptionsCollection()
			->extractProductPurchaseOptions($this->_productOptions->productId);
		if (null === $productPurchaseOptions) {
			throw new DomainException("Среди опций покупки отсутствует продукт с идентификатором {$this->_productOptions->productId}");
		}

		$result = $this->_apiConnector->makePurchase($this->_productOptions, $productPurchaseOptions);

		return $result->getPurchaseId();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function unsubscribe(): void
	{
		//ничего не отправляем партнеру, подписка просто протухнет, если мы ее принудительно не обновим
	}
}