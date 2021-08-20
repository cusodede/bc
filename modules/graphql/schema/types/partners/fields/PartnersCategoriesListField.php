<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\fields;

use app\models\common\RefPartnersCategories;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\partners\inputs\PartnersCategoriesFilterInput;
use app\modules\graphql\schema\types\partners\PartnerCategoryType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnersCategoriesListField
 * @package app\modules\graphql\schema\types\partners\fields
 */
class PartnersCategoriesListField extends BaseField
{
	/**
	 * @inheritDoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'partnersCategoriesList',
			'type' => Type::listOf(PartnerCategoryType::type()),
			'description' => 'Список категорий партнёра',
			'args' => [
				'filters' => [
					'type' => new PartnersCategoriesFilterInput(),
				],
			],
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo): array => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		return RefPartnersCategories::find()->andFilterWhere(['id' => static::filterValue($args, 'id')])->active()->all();
	}
}