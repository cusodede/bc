<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations\extended;

use app\models\subscriptions\Subscriptions;
use app\modules\graphql\base\BaseMutationType;
use app\modules\graphql\data\ErrorTypes;
use app\modules\graphql\data\MutationTypes;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;
use yii\db\ActiveRecord;

/**
 * Class SubscriptionMutationType
 * @package app\modules\graphql\schema\mutations\extended
 */
final class SubscriptionMutationType extends BaseMutationType
{

	/**
	 * {@inheritdoc}
	 */
	protected ?ActiveRecord $model;

	/**
	 * {@inheritdoc}
	 */
	public const MESSAGES = ['Ошибка сохранения подписки', 'Подписка успешно сохранена'];

	/**
	 * SubscriptionMutationType constructor.
	 * @param Subscriptions $model
	 */
	public function __construct(Subscriptions $model)
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
			'type' => MutationTypes::subscriptionMutation(),
			'args' => [
				'id' => Type::int(),
			],
			'description' => 'Мутации подписок',
			'resolve' => fn(Subscriptions $subscription = null, array $args = []): ?array => $args,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getArgs(): array
	{
		return [
			'trial_count' => [
				'type' => Type::int(),
				'description' => 'Количество триального периода',
			],
			'units' => [
				'type' => Type::int(),
				'description' => 'Единица измерения триального периода',
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
					'type' => ErrorTypes::validationErrorsUnionType(QueryTypes::subscription()),
					'description' => 'Обновление подписки',
					'args' => $this->getArgs(),
					'resolve' => fn(array $rootArgs, array $args = []): array => $this->update($rootArgs, $args),
				],
				'product' => [
					'type' => MutationTypes::productMutation(), // Связанная схема продуктов
					'description' => 'Обновление связанного продукта',
					'resolve' => fn(array $rootArgs): array => ['id' => $this->model::findOne($rootArgs)->product_id],
					// В resolve надо отдать связь продукта с подпиской
				],
			]
		];
	}
}