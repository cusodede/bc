<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutations\extended;

use app\models\products\EnumProductsTypes;
use Yii;
use app\models\products\Products;
use app\models\subscriptions\Subscriptions;
use app\modules\graphql\base\BaseMutationType;
use app\modules\graphql\data\ErrorTypes;
use app\modules\graphql\data\MutationTypes;
use app\modules\graphql\data\QueryTypes;
use GraphQL\Type\Definition\Type;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionMutationType
 * @package app\modules\graphql\schema\mutations\extended
 */
final class SubscriptionMutationType extends BaseMutationType
{
	/**
	 * {@inheritdoc}
	 */
	public const MESSAGES = ['Ошибка сохранения подписки', 'Подписка успешно сохранена'];

	/**
	 * SubscriptionMutationType constructor.
	 */
	public function __construct()
	{
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
			'resolve' => fn(Subscriptions $subscription = null, array $args = []): ?Subscriptions
				=> Subscriptions::findOne($args) ?? (empty($args) ? new Subscriptions() : null),
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
					'resolve' => fn(Subscriptions $subscriptions, array $args = []): array
						=> $this->save($subscriptions, $args, self::MESSAGES),
				],
				'product' => [
					'type' => MutationTypes::productMutation(),
					'description' => 'Обновление связанного продукта',
					'resolve' => fn(Subscriptions $subscription): Products => $subscription->product,
				],
				'create' => $this->create(),
			]
		];
	}

	private function create(): array
	{
		$productMutation = new ProductMutationType();
		$allArgs = ArrayHelper::merge($productMutation->getArgs(), $this->getArgs());

		$resolve = function(Subscriptions $subscription, array $args = []) use($productMutation): array {

			/** @var Transaction $transaction */
			$transaction  = Yii::$app->db->beginTransaction();

			$productModel = new Products();
			$productModel->type_id = EnumProductsTypes::TYPE_SUBSCRIPTION;
			$saveProducts = $this->save($productModel, $args, $productMutation::MESSAGES);
			if (false === ArrayHelper::getValue($saveProducts, 'result')) {
				return $saveProducts;
			}

			$subscription->setProduct($productModel);
			$saveSubscription = $this->save($subscription, $args, self::MESSAGES);

			if (false === ArrayHelper::getValue($saveSubscription, 'result')) {
				$transaction->rollBack();
				return $saveSubscription;
			}

			$transaction->commit();
			return $saveSubscription;
		};

		return [
			'type' => ErrorTypes::validationErrorsUnionType(QueryTypes::subscription()),
			'description' => 'Создание подписки',
			'args' => $allArgs,
			'resolve' => $resolve,
		];
	}
}