<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\common;

use app\modules\graphql\data\ErrorTypes;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Тип для серверной валидации моделей, список ValidationErrorType.
 * Class Response
 * @package app\modules\graphql\schema\common
 */
class Response extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'result' => [
					'type' => Type::boolean(),
					'description' => 'Результат запроса',
				],
				'message' => [
					'type' => Type::string(),
					'description' => 'Ответное сообщение',
				],
				'errors' => [
					'type' => Type::listOf(ErrorTypes::validationError()),
					'description' => 'Массив ошибок, если есть',
				],
			],
		]);
	}
}
