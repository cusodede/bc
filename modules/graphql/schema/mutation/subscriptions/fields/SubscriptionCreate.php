<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions\fields;

use app\models\products\Products;
use app\models\subscriptions\Subscriptions;
use app\modules\graphql\schema\mutation\products\fields\ProductUpdate;
use Yii;
use app\models\products\EnumProductsTypes;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\subscriptions\inputs\SubscriptionsProductsInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionCreate
 * @package app\modules\graphql\schema\mutation\subscriptions\fields
 */
class SubscriptionCreate extends BaseMutationType
{
	public const MESSAGES = ['Ошибка сохранения подписки', 'Подписка успешно сохранена'];

	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'create',
			'description' => 'Создание подписки',
			'type' => ResponseType::type(),
			'args' => [
				'data' => [
					'type' => Type::nonNull(new SubscriptionsProductsInput('Create')),
				]
			],
		]);
	}

	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?array
	{
		/** @var Transaction $transaction */
		$transaction = Yii::$app->db->beginTransaction();

		$productModel = new Products();
		$productModel->type_id = EnumProductsTypes::TYPE_SUBSCRIPTION;
		$data = ArrayHelper::getValue($args, 'data', []);
		$saveProducts = static::save($productModel, $data, ProductUpdate::MESSAGES);

		if (false === ArrayHelper::getValue($saveProducts, 'result', false)) {
			return $saveProducts;
		}

		$subscription = new Subscriptions();
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