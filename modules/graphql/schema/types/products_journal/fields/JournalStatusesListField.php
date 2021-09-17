<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal\fields;

use app\models\products\EnumProductsStatuses;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\products_journal\inputs\JournalStatusesFilterInput;
use app\modules\graphql\schema\types\products_journal\ProductJournalStatusType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Статусы журнала продуктов.
 */
class JournalStatusesListField extends BaseField
{
	/**
	 * @inheritDoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'productJournalStatusesList',
			'type' => Type::listOf(ProductJournalStatusType::type()),
			'description' => 'Список статусов истории операций',
			'args' => [
				'filters' => [
					'type' => new JournalStatusesFilterInput(),
				],
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		return static::enumResolve(EnumProductsStatuses::mapData(), static::filterValue($args, 'id'));
	}
}