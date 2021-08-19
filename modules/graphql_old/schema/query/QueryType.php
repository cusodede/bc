<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query;

use app\modules\graphql\data\QueryTypes;
use app\modules\graphql\schema\query\extended\UserType;
use app\modules\graphql\schema\query\extended\PartnerCategoryType;
use app\modules\graphql\schema\query\extended\enum\ProductPaymentPeriodType;
use app\modules\graphql\schema\query\extended\ProductType;
use app\modules\graphql\schema\query\extended\enum\ProductTypesType;
use app\modules\graphql\schema\query\extended\enum\SubscriptionTrialUnitsType;
use app\modules\graphql\schema\query\extended\PartnerType;
use app\modules\graphql\schema\query\extended\ServerDateTimeType;
use app\modules\graphql\schema\query\extended\SubscriptionType;
use GraphQL\Type\Definition\ObjectType;

/**
 * Class QueryType
 * @package app\modules\graphql\schema\query
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
				'subscriptions'				=> SubscriptionType::getListOfType(),
				'subscription'				=> SubscriptionType::getOneOfType(),
				'subscriptionTrialUnits' 	=> SubscriptionTrialUnitsType::getListOfType(),
				'subscriptionTrialUnit' 	=> SubscriptionTrialUnitsType::getOneOfType(),
				'serverDateTime' 			=> ServerDateTimeType::baseFormat(),
				'formPartnersField' 		=> QueryTypes::formPartners(),
				'formSubscriptionsField' 	=> QueryTypes::formSubscriptions(),
				'users'						=> UserType::getListOfType(),
				'user'						=> UserType::getOneOfType(),
			],
		]);
	}
}
