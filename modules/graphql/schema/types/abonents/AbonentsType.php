<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\abonents;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\abonents\fields\AbonentsListField;

/**
 * тип для абонентов.
 */
class AbonentsType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Абоненты',
			'fields' => [
				'productsJournalList' => AbonentsListField::field(),
			]
		]);
	}
}