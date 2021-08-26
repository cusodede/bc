<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\mutation\partners\PartnersMutationType;
use app\modules\graphql\schema\mutation\products\ProductsMutationType;

/**
 * Class MutationType
 * @package app\modules\graphql\schema\types
 */
class MutationType extends BaseObjectType
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct()
	{
		$schema = [
			'partners' => 	PartnersMutationType::root(),
			'product' =>	ProductsMutationType::root(),
		];

		ksort($schema, SORT_REGULAR);

		parent::__construct(['fields' => $schema]);
	}
}
