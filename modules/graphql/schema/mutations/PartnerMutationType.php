<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\models\partners\Partners;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use app\modules\graphql\schema\types\Types;

/**
 * Class PartnerMutationType
 * @package app\modules\graphql\schema\mutations
 */
class PartnerMutationType extends ObjectType implements MutationInterface
{
	use MutationTrait;

	/**
	 * Список сообщений для popup на фронте
	 */
	public const MESSAGES = ['Ошибка сохранения партнера', 'Партнер успешно сохранен'];

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
					'args' => $this->getArgs(),
					'resolve' => fn(Partners $partner, array $args = []) => $this->save($partner, $args, $this->getMessages()),
				],
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getArgs(): array
	{
		return [
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
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMessages(): array
	{
		return self::MESSAGES;
	}
}