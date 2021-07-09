<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\modules\graphql\schema\common\ValidationErrorType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;

/**
 * Class ErrorTypes
 * @package app\modules\graphql\data
 */
class ErrorTypes
{
	// Префикс для генерируем схемы ошибок
	public const SCHEMA_ERROR_NAME = 'ValidationErrorType';

	// Валидация
	private static ?ValidationErrorType $validationError = null;
	private static array $validationTypes = [];

	/**
	 * Объект валидации
	 * @return ValidationErrorType
	 */
	public static function validationError(): ValidationErrorType
	{
		return static::$validationError ?: static::$validationError = new ValidationErrorType();
	}

	/**
	 * Наш Union Type, для всех моделей.
	 * Метод возвращает новый сгенерированный тип, на основе типа, который пришел в аргументе.
	 * В resolveType, в случае успеха, нам придет наш сохраненный/измененный объект.
	 * В случае ошибок валидации придет ассоциативный массив из $model->getError()
	 * @param ObjectType $type
	 * @return UnionType
	 */
	public static function validationErrorsUnionType(ObjectType $type): UnionType
	{
		$typeNameValidationErrorsType = $type->name . self::SCHEMA_ERROR_NAME;
		return static::$validationTypes[$typeNameValidationErrorsType] ??
			static::$validationTypes[$typeNameValidationErrorsType] = new UnionType([
				'name' => $typeNameValidationErrorsType,
				'types' => [$type, ResponseTypes::response()],
				'resolveType' => fn($value) => ResponseTypes::response()
			]);
	}
}
