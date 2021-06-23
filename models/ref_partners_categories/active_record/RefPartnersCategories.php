<?php
declare(strict_types = 1);

namespace app\models\ref_partners_categories\active_record;

use pozitronik\references\models\Reference;

/**
 * Class RefPartnersCategories
 * @package app\models\ref_partners_categories\active_record
 */
class RefPartnersCategories extends Reference
{
	public $menuCaption  = 'Категории партнеров';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ref_partners_categories';
	}
}
