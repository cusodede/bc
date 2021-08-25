<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions\fields;

use app\models\subscriptions\Subscriptions;
use app\modules\graphql\components\BaseField;
use app\modules\graphql\schema\types\subscriptions\SubscriptionType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class SubscriptionProfileField
 * @package app\modules\graphql\schema\types\subscriptions\fields
 */
class SubscriptionProfileField extends BaseField
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'name' => 'subscriptionProfile',
			'type' => SubscriptionType::type(),
			'args' => [
				'id' => Type::nonNull(Type::int()),
			],
			'description' => 'Возвращает информацию о подписки по идентификатору.',
			'resolve' => fn(mixed $root, array $args, mixed $context, ResolveInfo $resolveInfo) => static::resolve(
				$root, $args, $context, $resolveInfo
			)
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): ?ActiveRecord
	{
		return Subscriptions::findOne(ArrayHelper::getValue($args, 'id', 0));
	}
}