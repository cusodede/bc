<?php
declare(strict_types = 1);

namespace app\models\common;

use pozitronik\references\models\Reference;

/**
 * Class RefPartnersCategories
 * @package app\models\ref_partners_categories\active_record
 */
class RefPartnersCategories extends Reference
{
	public string $menuCaption  	= 'Категории партнеров';
	protected ?string $_moduleId 	= null;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName(): string
	{
		return 'ref_partners_categories';
	}
}
