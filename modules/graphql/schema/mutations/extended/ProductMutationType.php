<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations\extended;

use app\models\products\Products;
use app\modules\graphql\base\BaseMutationType;
use app\modules\graphql\data\ErrorTypes;
use app\modules\graphql\data\MutationTypes;
use app\modules\graphql\data\QueryTypes;
use app\modules\graphql\definition\DateTimeType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductMutationType
 * @package app\modules\graphql\schema\mutations\extended
 */
final class ProductMutationType extends BaseMutationType
{
	/**
	 * {@inheritdoc}
	 */
	public const MESSAGES = ['Ошибка сохранения продукта', 'Продукт успешно сохранен'];

	/**
	 * ProductMutationType constructor.
	 */
	public function __construct()
	{
		parent::__construct(
			$this->getConfig()
		);
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
			'resolve' => fn(Products $product = null, array $args = []): ?Products => Products::findOne($args) ?? (empty($args) ? new Products() : null),
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
				'type' => DateTimeType::dateTime(),
				'description' => 'Начало действия Y-m-d H:i:s',
			],
			'end_date' => [
				'type' => DateTimeType::dateTime(),
				'description' => 'Конец действия Y-m-d H:i:s',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'Краткое описание продукта',
			],
			'ext_description' => [
				'type' => Type::string(),
				'description' => 'Полное описание продукта',
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
					'resolve' => fn(Products $product, array $args = []): array => $this->save($product, $args, self::MESSAGES),
				],
			]
		];
	}
}