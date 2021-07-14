<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use app\models\products\Products;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Abonents
 * @package app\models\abonents
 *
 * @property-read RelAbonentsToProducts[] $relatedAbonentsToProducts
 * @property-read Products[] $existentProducts закрепленные за абонентом продукты с фиксацией актуального статуса по каждому из них.
 * @property-read Products[] $unrelatedProducts список не связанных с абонентом продуктов.
 */
class Abonents extends ActiveRecordAbonents
{
	/**
	 * {@inheritdoc}
	 */
	public function getRelatedAbonentsToProducts(): ActiveQuery
	{
		return $this->hasMany(RelAbonentsToProducts::class, ['abonent_id' => 'id']);
	}

	/**
	 * @return Products[]
	 * @throws InvalidConfigException
	 */
	public function getUnrelatedProducts(): array
	{
		return Products::find()
			->where(['NOT IN', 'id', ArrayHelper::getColumn($this->relatedAbonentsToProducts, 'product_id')])
			->whereActivePeriod()
			->indexBy('id')
			->all();
	}

	/**
	 * @return Products[]
	 */
	public function getFullProductList(): array
	{
		return $this->existentProducts + $this->unrelatedProducts;
	}

	/**
	 * Получение закрепленных за абонентом продуктов с фиксацией актуального статуса по каждому из них.
	 * @return Products[]
	 */
	public function getExistentProducts(): array
	{
		return ArrayHelper::index(
			array_map(
				static function (RelAbonentsToProducts $relation) {
					$relation->relatedProduct->actualStatus = $relation->relatedLastProductsJournal;
					return $relation->relatedProduct;
				},
				$this->relatedAbonentsToProducts
			),
			'id'
		);
	}

	/**
	 * @param int $productId
	 * @return Products|null
	 */
	public function getExistentProductById(int $productId): ?Products
	{
		return $this->existentProducts[$productId] ?? null;
	}

	/**
	 * @param string $phone
	 * @return static|null
	 */
	public static function findByPhone(string $phone): ?self
	{
		return static::findOne(['phone' => $phone]);
	}
}