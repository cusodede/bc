<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions\fields;

use app\models\subscriptions\Subscriptions;
use app\modules\graphql\components\BaseMutationType;
use app\modules\graphql\schema\mutation\subscriptions\inputs\SubscriptionsInput;
use app\modules\graphql\schema\types\common\ResponseType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use yii\db\Exception;
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
					'description' => 'Идентификатор подписки',
				],
				'data' => [
					'type' => Type::nonNull(new SubscriptionsInput('Update')),
				]
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public static function resolve(mixed $root = null, array $args = [], mixed $context = null, ?ResolveInfo $resolveInfo = null): array
	{
		if (null === ($partner = Subscriptions::findOne(ArrayHelper::getValue($args, 'id', 0)))) {
			throw new Exception("Не найдена модель для обновления.");
		}
		return static::save($partner, ArrayHelper::getValue($args, 'data', []), self::MESSAGES);
	}
}