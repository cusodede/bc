<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\products_journal;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\products_journal\fields\ProductsJournalListField;

/**
 * Журнал по продуктам абонентов.
 */
class ProductsJournalType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Журнал подписок абонента',
			'fields' => [
				'productsJournalList' => ProductsJournalListField::field(),
			]
		]);
	}
}