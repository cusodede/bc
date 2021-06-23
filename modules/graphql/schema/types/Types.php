<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use app\modules\graphql\schema\mutations\PartnerMutationType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;
use yii\helpers\ArrayHelper;

/**
 * Class Types
 * @package app\modules\graphql\schema\types
 */
class Types
{
	public const SCHEMA_ERROR_NAME = 'ValidationErrorType';

	// Запрос, мутации, ответы
	private static ?QueryType $query = null;
	private static ?MutationType $mutation = null;
	private static ?ResponseType $response = null;

	// Валидация
	private static ?ValidationErrorType $validationError = null;
	private static ?ValidationErrorsListType $validationErrorsList = null;
	private static array $valitationTypes = [];

	// Типы для наших сущностей
	private static ?PartnerType $partner = null;
	private static ?PartnerMutationType $partnerMutation = null;
	private static ?PartnerCategoryType $partnerCategory = null;

	/**
	 * Запрос
	 * @return QueryType
	 */
	public static function query(): QueryType
	{
		return static::$query ?: static::$query = new QueryType();
	}

	/**
	 * Мутации
	 * @return MutationType
	 */
	public static function mutation(): MutationType
	{
		return static::$mutation ?: static::$mutation = new MutationType();
	}

	/**
	 * Успешный ответ при мутациях
	 * @return ResponseType
	 */
	public static function response(): ResponseType
	{
		return static::$response ?: static::$response = new ResponseType();
	}

	/**
	 * Объект валидации
	 * @return ValidationErrorType
	 */
	public static function validationError(): ValidationErrorType
	{
		return static::$validationError ?: static::$validationError = new ValidationErrorType();
	}

	/**
	 * Список объектов валидации
	 * @return ValidationErrorsListType
	 */
	public static function validationErrorsList(): ValidationErrorsListType
	{
		return self::$validationErrorsList ?: static::$validationErrorsList = new ValidationErrorsListType();
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
		return static::$valitationTypes[$typeNameValidationErrorsType] ??
			static::$valitationTypes[$typeNameValidationErrorsType] = new UnionType([
				'name' => $typeNameValidationErrorsType,
				'types' => [$type, static::validationErrorsList(), static::response()],
				'resolveType' => fn($value) => ArrayHelper::getValue($value, 'result', false) ?
					static::response() : static::validationErrorsList()
			]);
	}

	/**
	 * Запросы партнера
	 * @return PartnerType
	 */
	public static function partner(): PartnerType
	{
		return static::$partner ?: static::$partner = new PartnerType();
	}

	/**
	 * Мутации партнера
	 * @return PartnerMutationType
	 */
	public static function partnerMutation(): PartnerMutationType
	{
		return static::$partnerMutation ?: static::$partnerMutation = new PartnerMutationType();
	}

	/**
	 * Категории партнеров
	 * @return PartnerCategoryType
	 */
	public static function partnerCategory(): PartnerCategoryType
	{
		return static::$partnerCategory ?: static::$partnerCategory = new PartnerCategoryType();
	}
}
