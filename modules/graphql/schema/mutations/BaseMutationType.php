<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use GraphQL\Type\Definition\ObjectType;

/**
 * Class BaseMutationType
 * @package app\modules\graphql\schema\mutations
 */
abstract class BaseMutationType extends ObjectType
{
	/**
	 * ActiveRecord модель изменяемых данных
	 * @var string|null
	 */
	public ?string $model = null;

	/**
	 * Список сообщение для фронта
	 */
	public const MESSAGES = [];

	/**
	 * Схема для мутаций
	 * @return array
	 */
	abstract public static function mutationType(): array;

	/**
	 * Список атрибутов GraphQL типа
	 * @return array
	 */
	abstract public function getArgs(): array;
}