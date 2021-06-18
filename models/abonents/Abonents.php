<?php
declare(strict_types = 1);

namespace app\models\abonents;

use app\models\abonents\active_record\Abonents as ActiveRecordAbonents;
use app\models\products\Products;
use yii\db\ActiveQuery;

/**
 * Class Abonents
 * @package app\models\abonents
 *
 * @property RelAbonentsToProducts[] $relatedAbonentsToProducts
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
	 * @return Products[] получение закрепленных за абонентом продуктов с фиксацией актуального статуса по каждому из них.
	 */
	public function getExistentProducts(): array
	{
		return array_map(static function ($abonentsToProduct) {
			$abonentsToProduct->relatedProduct->actualStatus = $abonentsToProduct->relatedLastProductStatus;

			return $abonentsToProduct->relatedProduct;
		}, $this->relatedAbonentsToProducts);
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