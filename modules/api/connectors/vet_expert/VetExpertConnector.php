<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\vet_expert;

use app\modules\api\connectors\common\CommonConnector;

/**
 * Class VetExpertConnector
 * @package app\modules\api\connectors
 */
class VetExpertConnector extends CommonConnector
{
	/**
	 * {@inheritDoc}
	 */
	public function getApp(): string
	{
		return CommonConnector::APP_VET_EXPERT;
	}
}