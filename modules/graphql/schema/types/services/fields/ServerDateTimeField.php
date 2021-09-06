<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\services\fields;

use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\definition\DateTimeType;
use GraphQL\Type\Definition\ResolveInfo;
use DateTimeImmutable;

/**
 * Class ServerDateTimeField
 * @package app\modules\graphql\schema\types\services\fields
 */
class ServerDateTimeField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'serverDateTime',
			'description' 	=> 'Серверное время в формате ' . DateTimeType::DEFAULT_FORMAT,
			'type' => DateTimeType::type(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): DateTimeImmutable
	{
		return DateTimeType::parseString(date(DateTimeType::DEFAULT_FORMAT));
	}
}