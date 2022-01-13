<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Тип для серверной валидации моделей, список ValidationErrorType
 * Class Response
 * @package app\modules\graphql\schema\types
 */
class ResponseType extends BaseObjectType {
	/**
	 * {@inheritdoc}
	 */
	public function __construct() {
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
					'type' => Type::listOf(ValidationErrorType::type()),
					'description' => 'Массив ошибок, если есть',
				],
			],
		]);
	}
}
