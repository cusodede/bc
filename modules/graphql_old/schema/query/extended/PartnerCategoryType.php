<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use app\models\common\RefPartnersCategories;
use app\modules\graphql\base\BaseQueryType;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnerCategoryType
 * @package app\modules\graphql\schema\query\extended
 */
final class PartnerCategoryType extends BaseQueryType
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
			'type' => Type::listOf(QueryTypes::partnerCategory()),
			'description' => 'Возвращает список партнёров',
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
			'type' => QueryTypes::partnerCategory(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает категорию партнёра по id',
			'resolve' => fn(RefPartnersCategories $partnersCategories = null, array $args = []): ?RefPartnersCategories
				=> RefPartnersCategories::find()->where($args)->active()->one(),
		];
	}
}