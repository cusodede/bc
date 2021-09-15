<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Описание типа абонентов.
 */
class AbonentType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	protected function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::string(),
					'description' => 'Идентификатор абонента',
				],
				'name' => [
					'type' => Type::string(),
				],
				'surname' => [
					'type' => Type::string(),
				],
				'patronymic' => [
					'type' => Type::string(),
				],
				'phone' => [
					'type' => Type::string(),
				],
				'deleted' => [
					'type' => Type::boolean(),
				],
			],
		]);
	}
}