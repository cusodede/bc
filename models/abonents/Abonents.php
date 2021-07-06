<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use app\models\products\Products;
use pozitronik\helpers\ArrayHelper;
use yii\db\ActiveQuery;

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
	 */
	public function getUnrelatedProducts(): array
	{
		return Products::find()->where(['not in', 'id', ArrayHelper::getColumn($this->relatedAbonentsToProducts, 'product_id')])->all();
	}

	/**
	 * Получение закрепленных за абонентом продуктов с фиксацией актуального статуса по каждому из них.
	 * @return Products[]
	 */
	public function getExistentProducts(): array
	{
		return array_map(
			static function (RelAbonentsToProducts $abonentsToProduct) {
				$abonentsToProduct->relatedProduct->actualStatus = $abonentsToProduct->relatedLastProductsJournal;
				return $abonentsToProduct->relatedProduct;
			},
			//принудительно запрашиваем все линки для соответствия потребностям метода
			$this->getRelatedAbonentsToProducts()
				->indexBy('product_id')
				->all()
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