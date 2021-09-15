<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use app\models\phones\Phones;
use app\models\products\Products;
use Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Abonents
 * @package app\models\abonents
 *
 * @property-read Products[] $relatedProducts Список связанных продуктов с абонентом.
 */
class Abonents extends ActiveRecordAbonents
{
	/**
	 * @return Products[]
	 * @throws InvalidConfigException
	 */
	public function findFullProductList(): array
	{
		$existentProducts = $this->findExistentProducts();

		return $existentProducts + Products::find()
				->where(['NOT IN', 'id', ArrayHelper::getColumn($existentProducts, 'id')])
				->whereActivePeriod()
				->active()
				->indexBy('id')
				->all();
	}

	/**
	 * Получение закрепленных за абонентом продуктов с фиксацией актуального статуса по каждому из них.
	 * @return Products[]
	 */
	public function findExistentProducts(): array
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
		return static::findOne(['phone' => Phones::defaultFormat($phone)]);
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
