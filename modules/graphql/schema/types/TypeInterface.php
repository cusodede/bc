<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

/**
 * Интерфейс для типов GraphQL
 * Interface TypeInterface
 * @package app\modules\graphql\schema\types
 */
interface TypeInterface
{
	/**
	 * Список сущностей
	 * @return array
	 */
	public static function getListOfType(): array;

	public static function getOneOfType(): array;
}