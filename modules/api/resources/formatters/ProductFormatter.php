<?php
declare(strict_types = 1);

namespace app\modules\api\resources\formatters;

use app\components\helpers\DateHelper;
use app\components\helpers\FileHelper;
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
				'name',
				'description',
				'ext_description'    => static fn(Products $product) => HtmlPurifier::process(Markdown::convert($product->ext_description)),
				'type'               => 'typeName',
				'price',
				'typeRelatedOptions' => 'relatedInstance',
				'partner'            => 'relatedPartner',
				'subscription'       => 'actualStatus'
			],
			Subscriptions::class => [
				'trial_count'
			],
			Partners::class => [
				'name',
				'logo'     => static fn(Partners $partner) => FileHelper::mimeBase64($partner->fileLogo->path),
				'category' => 'relatedCategory'
			],
			RefPartnersCategories::class => [
				'name'
			],
			ProductsJournal::class => [
				'status'     => 'statusName',
				'expireDate' => static fn(ProductsJournal $status) => DateHelper::toIso8601($status->expire_date)
			]
		]);
	}
}