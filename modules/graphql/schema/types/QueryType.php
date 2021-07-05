<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\schema\types\extended\PartnerCategoryType;
use app\modules\graphql\schema\types\extended\ProductPaymentPeriodType;
use app\modules\graphql\schema\types\extended\ProductType;
use app\modules\graphql\schema\types\extended\ProductTypesType;
use app\modules\graphql\schema\types\extended\SubscriptionTrialUnitsType;
use app\modules\graphql\schema\types\extended\PartnerType;
use GraphQL\Type\Definition\ObjectType;

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
				'partners' 					=> PartnerType::getListOfType(),
				'partner' 					=> PartnerType::getOneOfType(),
				'partnerCategory' 			=> PartnerCategoryType::getOneOfType(),
				'partnersCategories' 		=> PartnerCategoryType::getListOfType(),
				'products' 					=> ProductType::getListOfType(),
				'product' 					=> ProductType::getOneOfType(),
				'productPaymentPeriods' 	=> ProductPaymentPeriodType::getListOfType(),
				'productPaymentPeriod' 		=> ProductPaymentPeriodType::getOneOfType(),
				'productTypes' 				=> ProductTypesType::getListOfType(),
				'productType' 				=> ProductTypesType::getOneOfType(),
				'subscriptionTrialUnits' 	=> SubscriptionTrialUnitsType::getListOfType(),
				'subscriptionTrialUnit' 	=> SubscriptionTrialUnitsType::getOneOfType(),
			],
		]);
	}
}