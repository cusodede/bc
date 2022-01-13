<?php
declare(strict_types = 1);

namespace app\modules\graphql\components;

use GraphQL\Error\Error;

/**
 * Class BasePaginatedObjectType
 * Класс объекта, возвращающего выборку данных + информацию о пагинации этой выборки.
 * Объявляет имена полей + проверяет их наличие в конфиге конструктора
 */
abstract class BasePaginatedObjectType extends BaseObjectType {
	public const EDGES_FIELD_NAME = 'edges';
	public const PAGINATION_FIELD_NAME = 'pagination';
	public const ORDER_FIELD_NAME = 'order';

	/**
	 * @inheritDoc
	 */
	public function __construct(array $config) {
		if (!(isset($config['fields'][self::EDGES_FIELD_NAME], $config['fields'][self::PAGINATION_FIELD_NAME]))) {
			throw new Error(static::class." should contain both ".self::EDGES_FIELD_NAME." and ".self::PAGINATION_FIELD_NAME." fields, as ".self::class." intended.");
		}
		parent::__construct($config);
	}
}