<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Фронты попросили в случае успешного обновление/создания объекта,
 * отдавать не обновлённый объект, а просто result => bool и message => string ...
 * Придёться добавить тип для такого ответа.
 * Class ResponseType
 * @package app\modules\graphql\schema\types
 */
class ResponseType extends ObjectType
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
					'description' => 'Результат запроса'
				],
				'message' => [
					'type' => Type::string(),
					'description' => 'Сообщение',
				],
			],
		]);
	}
}