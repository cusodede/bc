<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types\partners\fields;

use app\modules\graphql\schema\mutation\partners\inputs\PartnersInput;
use GraphQL\Type\Definition\EnumType;

/**
 * Class PartnerFormField
 * @package app\modules\graphql\schema\types\partners\fields
 */
class PartnerFormField extends EnumType
{
	public function __construct()
	{
		parent::__construct([
			'name' => 'FormPartnersField',
			'values' => array_keys((new PartnersInput('Create'))->getFields()),
		]);
	}
}