<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions\fields;

use app\components\helpers\ArrayHelper;
use app\modules\graphql\schema\mutation\products\inputs\ProductsInput;
use app\modules\graphql\schema\mutation\subscriptions\inputs\SubscriptionsInput;
use GraphQL\Type\Definition\EnumType;

/**
 * Class SubscriptionFormField
 * @package app\modules\graphql\schema\types\subscriptions\fields
 */
class SubscriptionFormField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'FormSubscriptionsField',
			'values' => array_keys(ArrayHelper::merge(
				(new ProductsInput('Create'))->getFields(),
				(new SubscriptionsInput('Create'))->getFields())
			),
		]);
	}
}