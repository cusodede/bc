<?php
declare(strict_types = 1);

namespace app\schema;

/**
 * Class Types
 * @package app\schema
 */
class Types {
	private static $_tokens;
	private static $_query;
	private static $_users;

	/**
	 * @return QueryType
	 */
	public static function query():QueryType {
		return self::$_query?:(self::$_query = new QueryType());
	}

	/**
	 * @return UsersType
	 */
	public static function users():UsersType {
		return self::$_users?:(self::$_users = new UsersType());
	}

	/**
	 * @return TokensType
	 */
	public static function tokens():TokensType {
		return self::$_tokens?:(self::$_tokens = new TokensType());
	}

}