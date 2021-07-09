<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations\extended;

use app\models\partners\Partners;
use app\modules\graphql\base\BaseMutationType;
use app\modules\graphql\data\ErrorTypes;
use app\modules\graphql\data\MutationTypes;
use GraphQL\Type\Definition\Type;
use app\modules\graphql\data\QueryTypes;
use yii\db\ActiveRecord;

/**
 * Class PartnerMutationType
 * @package app\modules\graphql\schema\mutations\extended
 */
final class PartnerMutationType extends BaseMutationType
{
	/**
	 * {@inheritdoc}
	 */
	protected ?ActiveRecord $model;

	/**
	 * {@inheritdoc}
	 */
	public const MESSAGES = ['Ошибка сохранения партнера', 'Партнер успешно сохранен'];

	/**
	 * PartnerMutationType constructor.
	 * @param Partners $model
	 */
	public function __construct(Partners $model)
	{
		$this->model = $model;
		parent::__construct($this->getConfig());
	}

	/**
	 * {@inheritdoc}
	 */
	public static function mutationType(): array
	{
		return [
			'type' => MutationTypes::partnerMutation(),
			'args' => [
				'id' => Type::int(),
			],
			'description' => 'Мутации партнёра',
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

	/**
	 * {@inheritdoc}
	 */
	public function getConfig(): array
	{
		return [
			'fields' => [
				'update' => [
					'type' => ErrorTypes::validationErrorsUnionType(QueryTypes::partner()),
					'description' => 'Обновление партнера',
					'args' => $this->getArgs(),
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->update($rootArgs, $args),
				],
				'create' => [
					'type' => ErrorTypes::validationErrorsUnionType(QueryTypes::partner()),
					'description' => 'Создание партнера',
					'args' => $this->getArgs(),
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->create($args),
				],
			]
		];
	}
}