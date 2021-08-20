<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\types\partners\fields\PartnersListField;

/**
 * Class PartnersType
 */
class PartnersType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Партнёры',
			'fields' => [
				'partnersList' => PartnersListField::field(),
			]
		]);
	}
}