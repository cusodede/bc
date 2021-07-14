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
				'type' => 'typeDesc',
				'name',
				'description',
				'ext_description' => static function(Products $p) {
					return HtmlPurifier::process(Markdown::convert($p->ext_description));
				},
				'price',
				'paymentPeriod' => 'paymentPeriodDesc',
				'options' => 'relatedInstance',
				'partner' => 'relatedPartner',
				'subscription' => 'actualStatus'
			],
			Subscriptions::class => [
				'trial' => static function (Subscriptions $subscription) use ($product) {
					//т.к. по продукту не производилось подключение, то доступен триальный период
					if (null === $product->actualStatus) {
						return ['units' => $subscription->unitName, 'number' => $subscription->trial_count];
					}
					return null;
				}
			],
			Partners::class => [
				'name',
				'logoPath' => static function(Partners $partner) {
					return PartnersController::to('get-logo', ['id' => $partner->id]);
				},
				'category' => 'relatedCategory'
			],
			RefPartnersCategories::class => [
				'name'
			],
			ProductsJournal::class => [
				'status' => 'statusDesc',
				'expireDate' => static function(ProductsJournal $status) {
					return DateHelper::toIso8601($status->expire_date);
				}
			]
		]);
	}
}