<?php
declare(strict_types = 1);

namespace app\modules\api\tokenizers\grant_types;

use app\models\sys\users\Users;
use app\models\sys\users\UsersTokens;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * Class BaseGrantType
 * @package app\modules\api\tokenizers\grant_types
 */
abstract class BaseGrantType
{
	protected Request $_request;

	/**
	 * BaseGrantType constructor.
	 * @param Request $request
	 * @throws BadRequestHttpException
	 */
	public function __construct(Request $request)
	{
		$this->_request = $request;

		if ((null === $this->getUserAgent()) && !$this->getUser()->isTechUser) {
			throw new BadRequestHttpException('User-agent is not provided.');
		}
	}

	/**
	 * @return Users
	 */
	public function getUser(): Users
	{
		return Yii::$app->user->identity;
	}

	/**
	 * @return string|null
	 */
	public function getUserAgent(): ?string
	{
		return $this->_request->userAgent;
	}

	/**
	 * @return string|null
	 */
	abstract public function getRefreshToken(): ?string;

	/**
	 * Проверка наличия ограничений на характер запроса токена.
	 * @param UsersTokens $authToken
	 * @param UsersTokens|null $refreshToken
	 */
	abstract public function validate(UsersTokens $authToken, ?UsersTokens $refreshToken): void;
}