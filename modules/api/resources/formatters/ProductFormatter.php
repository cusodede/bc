<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\helpers\DateHelper;
use app\models\partners\Partners;
use app\models\products\Products;
use app\models\products\ProductsJournal;
use app\models\common\RefPartnersCategories;
use app\models\subscriptions\Subscriptions;
use pozitronik\helpers\ArrayHelper;

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
				'name',
				'description',
				'type' => 'typeName',
				'price',
				'typeRelatedOptions' => 'relatedInstance',
				'partner' => 'relatedPartner',
				'subscription' => 'actualStatus'
			],
			Subscriptions::class => [
				'trial_count'
			],
			Partners::class => [
				'name',
				'category' => 'relatedCategory'
			],
			RefPartnersCategories::class => [
				'name'
			],
			ProductsJournal::class => [
				'status'     => 'statusName',
				'expireDate' => static function (ProductsJournal $status) {
					return DateHelper::toIso8601($status->expire_date);
				}
			]
		]);
	}
}