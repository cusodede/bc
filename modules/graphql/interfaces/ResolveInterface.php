<?php
declare(strict_types = 1);

namespace app\modules\graphql\interfaces;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 * Interface ResolveInterface
 */
interface ResolveInterface
{
	/**
	 * @param mixed $root
	 * @param Type[] $args Массив аргументов запроса
	 * @param mixed $context
	 * @param null|ResolveInfo $resolveInfo (параметр всегда передаётся, но он не обязателен, и можно сделать его nullable)
	 * @return array
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array;
}