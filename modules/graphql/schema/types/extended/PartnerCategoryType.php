<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\extended;

use app\models\common\RefPartnersCategories;
use app\modules\graphql\schema\common\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Категории партнеров
 * Class PartnerCategoryType
 * @package app\modules\graphql\schema\types
 */
final class PartnerCategoryType extends ObjectType
{
	/**
	 * PartnerCategoryType constructor.
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование категории',
				],
			],
		]);
	}

	/**
	 * @return array
	 */
	public static function getListOfType(): array
	{
		return [
			'type' => Type::listOf(Types::partnerCategory()),
			'resolve' => fn(RefPartnersCategories $partnersCategories = null, array $args = []): ?array
				=> RefPartnersCategories::find()->where($args)->active()->all(),
		];
	}

	/**
	 * @return array
	 */
	public static function getOneOfType(): array
	{
		return [
			'type' => Types::partnerCategory(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'resolve' => fn(RefPartnersCategories $partnersCategories = null, array $args = []): ?RefPartnersCategories
				=> RefPartnersCategories::find()->where($args)->active()->one(),
		];
	}
}