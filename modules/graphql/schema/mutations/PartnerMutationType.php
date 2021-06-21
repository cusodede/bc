<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\models\partners\Partners;
use app\modules\graphql\GraphqlHelper;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use app\modules\graphql\schema\types\Types;

/**
 * Class PartnerMutationType
 * @package app\modules\graphql\schema\mutations
 */
class PartnerMutationType extends ObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		parent::__construct([
			'fields' => [
				'update' => [
					'type' => Types::validationErrorsUnionType(Types::partner()),
					'description' => 'Обновление партнера',
					'args' => [
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
					],
					'resolve' => function(Partners $partner, array $args = []) {
						$partner->setAttributes($args);
						return $partner->save() ? GraphqlHelper::getResult(true, 'Партнер успешно обновлён') :
							GraphqlHelper::getErrors($partner->getErrors());
					}
				],
			]
		]);
	}
}