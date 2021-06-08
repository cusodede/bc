<?php
declare(strict_types = 1);

namespace app\models\ref_subscription_categories\active_record;

use pozitronik\references\models\Reference;

/**
 * Справочник категорий подписок
 * Class RefSubscriptionCategories
 * @package app\models\ref_subscription_categories\active_record
 */
class RefSubscriptionCategories extends Reference
{
	public string $menuCaption  = 'Категории подписок';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ref_subscription_categories';
	}
}
