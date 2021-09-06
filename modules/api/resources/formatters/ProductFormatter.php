<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\components\helpers\DateHelper;
use app\controllers\PartnersController;
use app\models\partners\Partners;
use app\models\products\Products;
use app\models\products\ProductsJournal;
use app\models\common\RefPartnersCategories;
use app\models\subscriptions\Subscriptions;
use kartik\markdown\Markdown;
use pozitronik\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * Class ProductFormatter
 * @package app\modules\api\resources\formatters
 */
class ProductFormatter implements ProductFormatterInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function format(Products $product): array
	{
		return ArrayHelper::toArray($product, [
			Products::class => [
				'id',
				'type' => 'type_id',
				'name',
				'description',
				'extDescription' => static fn(Products $p) => HtmlPurifier::process(Markdown::convert($p->ext_description)),
				'price',
				'paymentPeriod' => 'payment_period',
				'options' => 'relatedInstance',
				'partner' => 'relatedPartner',
				'subscriptionStatus' => 'actualStatus'
			],
			Subscriptions::class => [
				'trial' => static function (Subscriptions $subscription) use ($product) {
					//т.к. по продукту не производилось подключение, то доступен триальный период
					if (0 !== $subscription->trial_count) {
						return [
							'units' => $subscription->units,
							'count' => $subscription->trial_count,
							'expireDate' => (null !== $connectDate = $product->findFirstConnectDate())
								? $subscription->calculatePromoPeriodEndDate($connectDate)
								: null
						];
					}

					return null;
				}
			],
			Partners::class => [
				'name',
				'logoPath' => static fn(Partners $partner) => PartnersController::to('get-logo', ['id' => $partner->id], true),
				'category' => 'relatedCategory'
			],
			RefPartnersCategories::class => [
				'name'
			],
			ProductsJournal::class => [
				'status'     => static fn(ProductsJournal $status) => $status->isDisabled ? 0 : 1,
				'expireDate' => static fn(ProductsJournal $status) => DateHelper::toIso8601($status->expire_date)
			]
		]);
	}
}