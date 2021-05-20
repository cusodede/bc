<?php
declare(strict_types = 1);

namespace app\models\ref_products_types\active_record;

use pozitronik\references\models\Reference;
use yii\db\ActiveQuery;
use app\models\products\active_record\Products;

/**
 * Справочник типов продуктов
 *
 * @property int $id
 * @property string $name
 * @property int $deleted
 *
 * @property Products[] $products
 */
class RefProductsTypes extends Reference
{
	public string $menuCaption  = 'Типы продуктов';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ref_products_types';
	}

	/**
	 * Gets query for [[Products]].
	 *
	 * @return ActiveQuery
	 */
	public function getProducts(): ActiveQuery
	{
		return $this->hasMany(Products::class, ['type_id' => 'id']);
	}
}
