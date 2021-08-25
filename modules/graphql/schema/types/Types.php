<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;
use Throwable;

/**
 * Class Types
 * @package app\modules\graphql\schema\types
 */
class Types
{
	public const SCHEMA_ERROR_NAME = 'ValidationErrorType';

	// Валидация
	private static array $validationTypes = [];

	/**
	 * Наш Union Type, для всех моделей.
	 * Метод возвращает новый сгенерированный тип, на основе типа, который пришел в аргументе.
	 * В resolveType, в случае успеха, нам придет наш сохраненный/измененный объект.
	 * В случае ошибок валидации придет ассоциативный массив из $model->getError()
	 * @param ObjectType $type
	 * @return UnionType
	 * @throws Throwable
	 */
	public static function validationErrorsUnionType(ObjectType $type): UnionType
	{
		$typeNameValidationErrorsType = $type->name . self::SCHEMA_ERROR_NAME;
		return static::$validationTypes[$typeNameValidationErrorsType] ??
			static::$validationTypes[$typeNameValidationErrorsType] = new UnionType([
				'name' => $typeNameValidationErrorsType,
				'types' => [$type, ResponseType::type()],
				'resolveType' => fn($value) => ResponseType::type()
			]);
	}
}