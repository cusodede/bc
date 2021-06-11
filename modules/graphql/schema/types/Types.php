<?php
declare(strict_types = 1);

namespace app\modules\graphql\schema\types;

/**
 * Class Types
 * @package app\modules\graphql\schema\types
 */
class Types
{
	public static ?PartnerType $partner = null;
	public static ?QueryType $query = null;

	/**
	 * @return QueryType
	 */
	public static function query(): QueryType
	{
		return static::$query ?: static::$query = new QueryType();
	}

	/**
	 * @return PartnerType
	 */
	public static function partner(): PartnerType
	{
		return static::$partner ?: static::$partner = new PartnerType();
	}
}
