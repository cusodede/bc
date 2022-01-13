<?php
declare(strict_types = 1);

namespace app\models\sys\users;

use app\models\sys\users\active_record\UsersRemoteSystemsTokensAR;

/**
 * Таблица предназначается для хранения токенов сторонних систем.
 * Например, дол, дмп и т.д.
 * После авторизации пользователя, дол отдает нам accessToken, который
 * мы должны использовать для обращения к другим методам
 *
 * Class UserRemoteSystemToken
 * @package app\models\sys\users\active_record
 */
class UsersRemoteSystemsTokens extends UsersRemoteSystemsTokensAR {
	public const SYSTEM_DOL = 1;
	public const SYSTEM_DOL_ACCESS_TOKEN = 1;

	/**
	 * @param int $userId
	 * @param string $tokenValue
	 * @param int $seller_id
	 * @return static
	 */
	public static function dolAccessToken(int $userId, string $tokenValue, int $seller_id):self {
		$self = new self();
		$self->user_id = $userId;
		$self->token_value = $tokenValue;
		$self->seller_id = $seller_id;
		$self->remote_system_id = self::SYSTEM_DOL;
		$self->token_type_id = self::SYSTEM_DOL_ACCESS_TOKEN;
		$self->created_at = date('Y-m-d H:i:s');
		$self->expired_at = null;
		return $self;
	}
}
