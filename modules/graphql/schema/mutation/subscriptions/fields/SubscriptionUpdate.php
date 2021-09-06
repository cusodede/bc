<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions\fields;

use app\models\subscriptions\Subscriptions;
use Yii;
use app\models\products\EnumProductsTypes;
use app\models\products\Products;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\products\fields\ProductUpdate;
use app\modules\graphql\schema\mutation\subscriptions\inputs\SubscriptionsProductsInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionUpdate
 * @package app\modules\graphql\schema\mutation\subscriptions\fields
 */
class SubscriptionUpdate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения подписки', 'Подписка успешно сохранена'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'update',
			'description' => 'Обновление подписки',
			'type' => ResponseType::type(),
			'args' => [
				'id' => [
					'type' => Type::nonNull(Type::int()),
					'description' => 'Идентификатор продукта',
				],
				'data' => [
					'type' => Type::nonNull(new SubscriptionsProductsInput('Update')),
				]
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		$productId = ArrayHelper::getValue($args, 'id', 0);
		$productModel = Products::findOne(['id' => $productId, 'type_id' => EnumProductsTypes::TYPE_SUBSCRIPTION]);

		if (null === $productModel) {
			throw new Exception("Не найдена модель для обновления.");
		}

		/** @var Transaction $transaction */
		$transaction = Yii::$app->db->beginTransaction();

		$productModel->type_id = EnumProductsTypes::TYPE_SUBSCRIPTION;
		$data = ArrayHelper::getValue($args, 'data', []);
		$saveProducts = static::save($productModel, $data, ProductUpdate::MESSAGES);

		if (false === ArrayHelper::getValue($saveProducts, 'result', false)) {
			return $saveProducts;
		}

		if (null === ($subscription = Subscriptions::findOne(['product_id' => $productModel->id]))) {
			throw new Exception("Не найдена модель подписки для обновления.");
		}

		/** @var Subscriptions $subscription */
		$subscription->setProduct($productModel);
		$saveSubscription = static::save($subscription, $data, self::MESSAGES);

		if (false === ArrayHelper::getValue($saveSubscription, 'result', false)) {
			$transaction->rollBack();
			return $saveSubscription;
		}

		$transaction->commit();
		return $saveSubscription;
	}
}