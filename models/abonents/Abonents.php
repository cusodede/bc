<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use app\models\products\Products;
use Exception;
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
 * @property-read Products[] $fullProductList todo: что это?
 * @property-read Products[] $relatedProducts Список связанных продуктов с абонентом.
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
				static function(RelAbonentsToProducts $relation) {
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
	 * @throws Exception
	 */
	public function findExistentProductById(int $productId): ?Products
	{
		return ArrayHelper::getValue($this, "existentProducts.$productId");
	}

	/**
	 * @param string $phone
	 * @return static|null
	 */
	public static function findByPhone(string $phone): ?self
	{
		return static::findOne(['phone' => $phone]);
	}

	/**
	 * Получение ФИО пользователя
	 * @return string
	 */
	public function getFullName(): string
	{
		return "{$this->surname} {$this->name} {$this->patronymic}";
	}

	/**
	 * @return ActiveQuery
	 */
	public function getRelatedProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['id' => 'product_id'])->via('relatedAbonentsToProducts');
	}
}
