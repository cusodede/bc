<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations\extended;

use app\models\products\Products;
use app\modules\graphql\base\BaseMutationType;
use app\modules\graphql\data\ErrorTypes;
use app\modules\graphql\data\MutationTypes;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;
use yii\db\ActiveRecord;

/**
 * Class ProductMutationType
 * @package app\modules\graphql\schema\mutations\extended
 */
final class ProductMutationType extends BaseMutationType
{
	/**
	 * {@inheritdoc}
	 */
	protected ?ActiveRecord $model;

	/**
	 * {@inheritdoc}
	 */
	public const MESSAGES = ['Ошибка сохранения продукта', 'Продукт успешно сохранен'];

	/**
	 * ProductMutationType constructor.
	 * @param Products $model
	 */
	public function __construct(Products $model)
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
			'type' => MutationTypes::productMutation(),
			'args' => [
				'id' => Type::int(),
			],
			'description' => 'Мутации продуктов',
			'resolve' => fn(Products $product = null, array $args = []): ?array => $args,
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
				'description' => 'Наименование продукта',
			],
			'partner_id' => [
				'type' => Type::int(),
				'description' => 'Идентификатор партнёра',
			],
			'price' => [
				'type' => Type::float(),
				'description' => 'Цена',
			],
			'payment_period' => [
				'type' => Type::int(),
				'description' => 'Периодичность списания',
			],
			'start_date' => [
				'type' => Type::string(),
				'description' => 'Начало действия Y-m-d H:i:s',
			],
			'end_date' => [
				'type' => Type::string(),
				'description' => 'Конец действия Y-m-d H:i:s',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'Описание продукта',
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
					'type' => ErrorTypes::validationErrorsUnionType(QueryTypes::product()),
					'description' => 'Обновление продукта',
					'args' => $this->getArgs(),
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->update($rootArgs, $args),
				],
			]
		];
	}
}