<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\schema\types\extended\PartnerCategoryType;
use app\modules\graphql\schema\types\extended\ProductPaymentPeriodType;
use app\modules\graphql\schema\types\extended\ProductType;
use GraphQL\Type\Definition\ObjectType;
use app\modules\graphql\schema\types\extended\PartnerType;

/**
 * Class QueryType
 * @package app\schema
 */
class QueryType extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'partners' => PartnerType::getListOfType(),
				'partner' => PartnerType::getOneOfType(),
				'partnerCategory' => PartnerCategoryType::getOneOfType(),
				'partnersCategories' => PartnerCategoryType::getListOfType(),
				'products' => ProductType::getListOfType(),
				'product' => ProductType::getOneOfType(),
				'productPaymentPeriods' => ProductPaymentPeriodType::getListOfType(),
				'productPaymentPeriod' => ProductPaymentPeriodType::getOneOfType(),
			],
		]);
	}
}