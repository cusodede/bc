<?php
declare(strict_types = 1);

namespace app\models\products;

use app\models\products\active_record\Products as ActiveRecordProducts;
use app\models\subscriptions\Subscriptions;
use app\models\partners\Partners;
use app\models\sys\users\Users;
use Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Логика продуктов, не относящиеся к ActiveRecord
 * Class Products
 * @package app\models\product
 *
 * @property Partners $relatedPartner
 * @property Users $relatedUser
 * @property ProductStatuses|null $actualStatus актуальный статус продукта по абоненту.
 * @property-read string|null $typeName именованное обозначение типа продукта.
 * @property-read ActiveRecord|null $relatedInstance
 * @property-read bool $isSubscription флаг определения типа "Подписка" для продукта.
 */
class Products extends ActiveRecordProducts
{
	/**
	 * @var ProductStatuses|null актуальный статус продукта по абоненту.
	 */
	public ?ProductStatuses $actualStatus = null;

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedPartner(): ActiveQuery
	{
		return $this->hasOne(Partners::class, ['id' => 'partner_id']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRelatedUser(): ActiveQuery
	{
		return $this->hasOne(Users::class, ['id' => 'user_id']);
	}

	/**
	 * Получение расширенной модели продукта в зависимости от его типа.
	 * @return ActiveRecord|null
	 */
	public function getRelatedInstance(): ?ActiveRecord
	{
		if ($this->isSubscription) {
			return Subscriptions::findOne(['product_id' => $this->id]);
		}

		return null;
	}

	/**
	 * @return string|null именованное обозначение типа продукта.
	 * @throws Exception
	 */
	public function getTypeName(): ?string
	{
		return EnumProductsTypes::getTypeName($this->type_id);
	}

	/**
	 * @return bool
	 */
	public function getIsSubscription(): bool
	{
		return EnumProductsTypes::TYPE_SUBSCRIPTION === $this->type_id;
	}
}