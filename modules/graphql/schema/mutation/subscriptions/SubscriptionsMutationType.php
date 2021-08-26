<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\subscriptions;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\mutation\subscriptions\fields\SubscriptionCreate;
use app\modules\graphql\schema\mutation\subscriptions\fields\SubscriptionUpdate;

/**
 * Class SubscriptionsMutationType
 * @package app\modules\graphql\schema\mutation\subscriptions
 */
class SubscriptionsMutationType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Мутации подписок',
			'fields' => [
				'update' => SubscriptionUpdate::field(),
				'create' => SubscriptionCreate::field(),
			]
		]);
	}
}