<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Тип для серверной валидации моделей, список ValidationErrorType
 * Class ValidationErrorsListType
 * @package app\modules\graphql\schema\types
 */
class ValidationErrorsListType extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'errors' => Type::listOf(Types::validationError()),
			],
		]);
	}
}