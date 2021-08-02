<?php
declare(strict_types = 1);

namespace app\modules\api\tokenizers\grant_types;

use app\models\sys\users\UsersTokens;
use app\modules\api\exceptions\InvalidGrantException;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * Class GrantTypeRefresh
 * @package app\modules\api\tokenizers\grant_types
 */
class GrantTypeRefresh extends BaseGrantType
{
	/**
	 * GrantTypeRefresh constructor.
	 * @param Request $request
	 * @throws BadRequestHttpException
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);

		if (null === $this->getRefreshToken()) {
			throw new BadRequestHttpException('refresh_token param is invalid');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRefreshToken(): ?string
	{
		return $this->_request->post('refresh_token');
	}

	/**
	 * {@inheritdoc}
	 * @param UsersTokens $authToken
	 * @param UsersTokens|null $refreshToken
	 * @throws BadRequestHttpException
	 * @throws InvalidGrantException
	 */
	public function validate(UsersTokens $authToken, ?UsersTokens $refreshToken): void
	{
		if (null === $refreshToken || $refreshToken->isNewRecord) {
			$statusIsOk = false;
		} else {
			if ($refreshToken->user_agent !== $this->getUserAgent()) {
				throw new BadRequestHttpException();
			}

			$statusIsOk = $refreshToken->auth_token === $this->getRefreshToken() && $refreshToken->isValid();
		}
		if (!$statusIsOk) {
			throw new InvalidGrantException();
		}
	}
}