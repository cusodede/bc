<?php
declare(strict_types = 1);

namespace app\components\subscription;

use app\models\products\Products;
use app\modules\api\connectors\ivi\IviConnector;
use app\modules\api\connectors\ivi\ProductOptions;
use DomainException;

/**
 * Class IviSubscriptionHandler
 * @package app\components\subscription
 */
class IviSubscriptionHandler extends SubscriptionHandler
{
	private IviConnector $_apiConnector;

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
	protected function subscribe(): void
	{
		$productOptions = new ProductOptions(['product' => $this->_product->id, 'phone' => $this->_abonent->phone]);

		//делаем запрос на получение актуального списка опций
		$response = $this->_apiConnector->getPurchaseOptions($productOptions);

		$purchaseOptions = $response->getPurchasesOptionsCollection();
		if ($purchaseOptions->isEmpty()) {
			throw new DomainException('Отсутствуют доступные для подключения опции.');
		}

		$productPurchaseOptions = $purchaseOptions->extractProductPurchaseOptions($productOptions->product);
		if (null === $productPurchaseOptions) {
			throw new DomainException("Среди опций покупки отсутствует продукт с идентификатором {$productOptions->product}");
		}

		$purchaseResult = $this->_apiConnector->makePurchase($productOptions, $productPurchaseOptions);
		$purchaseResult->getPurchaseId();//TODO предусмотреть сохранение идентификатора продукта в системе
	}

	/**
	 * {@inheritdoc}
	 */
	protected function unsubscribe(): void
	{
		//ничего не отправляем партнеру, подписка просто протухнет, если мы ее принудительно не обновим
	}
}