<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations;

use app\models\partners\Partners;
use GraphQL\Type\Definition\Type;
use app\modules\graphql\schema\types\Types;

/**
 * Class PartnerMutationType
 * @package app\modules\graphql\schema\mutations
 */
final class PartnerMutationType extends MutationType
{
	use MutationTrait;

	/**
	 * {@inheritdoc}
	 */
	public ?string $model = Partners::class;

	/**
	 * {@inheritdoc}
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
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->update($rootArgs, $args),
				],
				'create' => [
					'type' => Types::validationErrorsUnionType(Types::partner()),
					'description' => 'Создание партнера',
					'args' => $this->getArgs(),
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->create($args),
				],
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function mutationType(): array
	{
		return [
			'type' => Types::partnerMutation(),
			'args' => [
				'id' => Type::int(),
			],
			'resolve' => fn(Partners $partner = null, array $args = []): ?array => $args,
		];
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
}