<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\mutation\partners;

use app\modules\graphql\components\BaseObjectType;
use app\modules\graphql\schema\mutation\partners\fields\PartnerCreate;
use app\modules\graphql\schema\mutation\partners\fields\PartnerUpdate;

/**
 * Class PartnersMutationType
 * @package app\modules\graphql\schema\mutation\partners
 */
class PartnersMutationType extends BaseObjectType
{
	/**
	 * @inheritdoc
	 */
	public function __construct()
	{
		parent::__construct([
			'description' => 'Мутации партнёра',
			'fields' => [
				'update' => PartnerUpdate::field(),
				'create' => PartnerCreate::field()
			]
		]);
	}
}