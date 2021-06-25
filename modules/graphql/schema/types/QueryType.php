<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\models\partners\Partners;
use app\models\partners\PartnersSearch;
use app\models\ref_partners_categories\active_record\RefPartnersCategories;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

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
				'partners' => [
					'type' => Type::listOf(Types::partner()),
					'args' => [
						'search' => Type::string(),
					],
					'resolve' => fn(Partners $partner = null, array $args = []): ?array
						=> PartnersSearch::searchWithParams($args),
				],
				'partner' => [
					'type' => Types::partner(),
					'args' => [
						'id' => Type::nonNull(Type::int()),
					],
					'resolve' => fn(Partners $partner = null, array $args = []): ?Partners
						=> Partners::find()->where($args)->active()->one(),
				],
				'partnerCategory' => [
					'type' => Types::partnerCategory(),
					'args' => [
						'id' => Type::nonNull(Type::int()),
					],
					'resolve' => fn(RefPartnersCategories $partnersCategories = null, array $args = []): ?RefPartnersCategories
						=> RefPartnersCategories::find()->where($args)->active()->one(),
				],
				'partnersCategories' => [
					'type' => Type::listOf(Types::partnerCategory()),
					'resolve' => fn(RefPartnersCategories $partnersCategories = null, array $args = []): ?array
						=> RefPartnersCategories::find()->where($args)->active()->all(),
				],
				'products' => ProductType::getListOfType(),
			],
		]);
	}
}