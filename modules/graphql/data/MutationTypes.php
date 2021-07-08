<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\models\partners\Partners;
use app\modules\graphql\schema\mutations\extended\PartnerMutationType;
use app\modules\graphql\schema\mutations\MutationType;

/**
 * Class MutationTypes
 * @package app\modules\graphql\data
 */
class MutationTypes
{
	// Главный тип мутация
	private static ?MutationType $mutation = null;

	private static ?PartnerMutationType $partnerMutation = null;

	/**
	 * Мутации
	 * @return MutationType
	 */
	public static function mutation(): MutationType
	{
		return static::$mutation ?: static::$mutation = new MutationType();
	}

	/**
	 * Мутации партнера
	 * @return PartnerMutationType
	 */
	public static function partnerMutation(): PartnerMutationType
	{
		return static::$partnerMutation ?: static::$partnerMutation = new PartnerMutationType(new Partners());
	}
}
