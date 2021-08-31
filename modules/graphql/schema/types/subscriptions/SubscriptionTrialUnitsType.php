<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\subscriptions;

use app\modules\graphql\components\BaseObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class SubscriptionTrialUnitsType
 * @package app\modules\graphql\schema\types\subscriptions
 */
class SubscriptionTrialUnitsType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	protected function __construct()
	{
		parent::__construct([
			'fields' => [
				'id' => [
					'type' => Type::int(),
					'description' => 'Идентификатор единицы измерения триального периода',
				],
				'name' => [
					'type' => Type::string(),
					'description' => 'Наименование единицы измерения триального периода',
				],
			],
		]);
	}
}