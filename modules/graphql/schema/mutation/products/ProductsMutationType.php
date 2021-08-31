<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\products;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\mutation\products\fields\ProductUpdate;

/**
 * Class ProductsMutationType
 * @package app\modules\graphql\schema\mutation\products
 */
class ProductsMutationType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Мутации продукта',
			'fields' => [
				'update' => ProductUpdate::field(),
			]
		]);
	}
}