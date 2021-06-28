<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\models\common\RefPartnersCategories;
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
				'partners' => PartnerType::getListOfType(),
				'partner' => PartnerType::getOneOfType(),
				'partnerCategory' => PartnerCategoryType::getOneOfType(),
				'partnersCategories' => PartnerCategoryType::getListOfType(),
				'products' => ProductType::getListOfType(),
				'product' => ProductType::getOneOfType(),
			],
		]);
	}
}