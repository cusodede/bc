<?php
declare(strict_types = 1);

namespace app\modules\api\mobile_api\resources;

use app\models\abonents\Abonents;
use app\models\products\Products;
use app\models\products\ProductStatuses;
use app\models\products\EnumProductsStatuses;
use app\models\products\EnumProductsTypes;
use DomainException;
use pozitronik\helpers\ArrayHelper;

/**
 * Class ProductsResource
 * @package app\modules\api\mybeeline\resources
 */
class ProductsResource
{
	public function getProductsByPhone(string $phone): array
	{
		$abonentModel = Abonents::find()
			->alias('ab')
			->joinWith([
				'relatedAbonentsToProducts.relatedLastProductStatus',
				'relatedAbonentsToProducts.relatedProduct'
			])
			->withPhone($phone)
			->one();

		if (null === $abonentModel) {
			throw new DomainException('Не удалось установить абонента по телефону: ' . $phone);
		}

		$response = array_map(function ($abonentsToProduct) {
			return $this->formatProduct($abonentsToProduct->relatedProduct, $abonentsToProduct->relatedLastProductStatus);
		}, $abonentModel->relatedAbonentsToProducts);

		$additionalProducts = Products::find()
			->where(['NOT IN', 'id', ArrayHelper::getColumn($abonentModel->relatedAbonentsToProducts, 'product_id')])
			->all();

		foreach ($additionalProducts as $product) {
			$response[] = $this->formatProduct($product);
		}

		return $response;
	}

	private function formatProduct(Products $product, ?ProductStatuses $productStatus = null): array
	{
		$array = ArrayHelper::toArray($product, [
			Products::class => [
				'name',
				'description',
				'type' => static function (Products $model) {
					return EnumProductsTypes::getTypeName($model->type_id);
				},
				'price',
				'typeRelatedOptions' => 'relatedInstance'
			]
		], false);

		if (null !== $productStatus) {
			$array['subscriptionOptions'] = [
				'status'     => EnumProductsStatuses::getStatusName($productStatus->status_id),
				'expireDate' => $productStatus->expire_date
			];
		}

		return $array;
	}
}