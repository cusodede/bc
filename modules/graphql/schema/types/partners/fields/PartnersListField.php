<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\fields;

use app\models\partners\PartnersSearch;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\partners\inputs\PartnersFilterInput;
use app\modules\graphql\schema\types\partners\PartnerType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\helpers\ArrayHelper;

/**
 * Class PartnersListField
 * @package app\modules\graphql\schema\types\partners\fields
 */
class PartnersListField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'partnersList',
			'type' => Type::listOf(PartnerType::type()),
			'description' => 'Список партнёров',
			'args' => [
				'filters' => [
					'type' => new PartnersFilterInput(),
				],
				'limit' => Type::nonNull(Type::int()),
				'offset' => Type::nonNull(Type::int())
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
		$partnerSearch = new PartnersSearch();
		$filters = ArrayHelper::getValue($args, 'filters', []);
		ArrayHelper::setValue($args, 'pagination', false);
		return $partnerSearch->search([$partnerSearch->formName() => ArrayHelper::merge($args, $filters)])->getModels();
	}
}