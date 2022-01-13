<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\common;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class Phone
 */
class PhoneType extends BaseObjectType {
	public function __construct() {
		parent::__construct([
			'fields' => [
				'number' => [
					'type' => Type::string(),
					'description' => 'Номер'
				],
				'status' => [
					'type' => Type::string(),
					'description' => 'Статус'
				]
			]
		]);
	}
}