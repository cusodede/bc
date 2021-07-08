<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\query\extended;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Служебный класс для данных даты.
 * Class ServerDateTimeType
 * @package app\modules\graphql\schema\query\extended
 */
final class ServerDateTimeType extends ObjectType
{
	/**
	 * PartnerCategoryType constructor.
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'value' => [
					'type' => Type::string(),
					'description' => 'Серверное время в формате Y-m-d H:i:s',
				],
			],
		]);
	}

	/**
	 * @return array
	 */
	public static function baseFormat(): array
	{
		return [
			'type' => Type::string(),
			'description' => 'Серверное время в формате Y-m-d H:i:s',
			'resolve' => fn(?array $root, array $args): string => date('Y-m-d H:i:s'),
		];
	}
}