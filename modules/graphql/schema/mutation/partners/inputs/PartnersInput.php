<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\partners\inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PartnersInput
 * @package app\modules\graphql\schema\mutation\partners\inputs
 */
class PartnersInput extends InputObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct(string $rootName)
	{
		parent::__construct([
			'name' => $rootName . 'PartnerData',
			'fields' => [
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование юридического лица партнера',
				],
				'inn' => [
					'type' => Type::string(),
					'description' => 'ИНН партнера',
				],
				'phone' => [
					'type' => Type::string(),
					'description' => 'Телефон поддержки партнера',
				],
				'email' => [
					'type' => Type::string(),
					'description' => 'Почтовый адрес поддержки партнера',
				],
				'comment' => [
					'type' => Type::string(),
					'description' => 'Комментарий',
				],
				'category_id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор категории',
				],
				'logo' => [
					'type' => Type::string(),
					'description' => 'Логотип партнёра',
				],
			]
		]);
	}
}