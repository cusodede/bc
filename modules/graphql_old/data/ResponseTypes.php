<?php
declare(strict_types = 1);

namespace app\modules\graphql\data;

use app\modules\graphql\schema\common\Response;

/**
 * Class ResponseTypes
 * @package app\modules\graphql\data
 */
class ResponseTypes
{
	private static ?Response $response = null;

	/**
	 * Список объектов валидации
	 * @return Response
	 */
	public static function response(): Response
	{
		return self::$response ?: static::$response = new Response();
	}
}