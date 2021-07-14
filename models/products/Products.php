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
 * @property ProductsJournal|null $actualStatus актуальный статус продукта по абоненту.
 * @property-read string|null $typeDesc именованное обозначение типа продукта.
 * @property-read string|null $paymentPeriodDesc
 * @property-read string $paymentDateModifier
 * @property-read ActiveRecord|null $relatedInstance
 * @property-read ActiveQuery $relatedSubscription
 * @property-read bool $isSubscription флаг определения типа "Подписка" для продукта.
 */
class Products extends ActiveRecordProducts
{
	/**
	 * @var ProductsJournal|null актуальный статус продукта по абоненту.
	 */
	public ?ProductsJournal $actualStatus = null;

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
	public function getTypeDesc(): ?string
	{
		return EnumProductsTypes::getScalar($this->type_id);
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	public function getPaymentPeriodDesc(): ?string
	{
		return EnumProductsPaymentPeriods::getScalar($this->payment_period);
	}

	/**
	 * @return bool
	 */
	public function getIsSubscription(): bool
	{
		return EnumProductsTypes::TYPE_SUBSCRIPTION === $this->type_id;
	}

	public function getRelatedSubscription(): ActiveQuery
	{
		return $this->hasOne(Subscriptions::class, ['product_id' => 'id']);
	}

	/**
	 * @return string
	 */
	public function getPaymentDateModifier(): string
	{
		switch ($this->payment_period) {
			case EnumProductsPaymentPeriods::TYPE_MONTHLY:
				$modify = ' + 1 month';
			break;
			case EnumProductsPaymentPeriods::TYPE_DAILY:
				$modify = ' + 1 day';
			break;
			default:
				$modify = ' + 0 days';
		}

		return $modify;
	}
}