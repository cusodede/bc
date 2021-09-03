<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\subscriptions\fields\SubscriptionFormField;
use app\modules\graphql\schema\types\subscriptions\fields\SubscriptionProfileField;
use app\modules\graphql\schema\types\subscriptions\fields\SubscriptionsListFieldListField;
use app\modules\graphql\schema\types\subscriptions\fields\SubscriptionsTrialUnitsListField;

/**
 * Class SubscriptionsType
 * @package app\modules\graphql\schema\types\subscription
 */
class SubscriptionsType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Подписки',
			'fields' => [
				'subscriptionList' 				=> SubscriptionsListFieldListField::field(),
			 	'subscriptionProfile' 			=> SubscriptionProfileField::field(),
				'subscriptionTrialUnitsList'	=> SubscriptionsTrialUnitsListField::field(),
				'subscriptionForm' 				=> new SubscriptionFormField(),
			]
		]);
	}
}