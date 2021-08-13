<?php
declare(strict_types = 1);

namespace app\modules\api\authenticators;

use app\modules\api\controllers\AuthController;
use app\modules\api\tokenizers\grant_types\GrantTypeIssue;
use app\modules\api\tokenizers\RefreshTokenType;
use Throwable;
use yii\filters\auth\AuthMethod;
use yii\web\IdentityInterface;

/**
 * Метод аутентификации, применимый только для фронтовых пользователей.
 * Class RefreshTokenAuth
 * @package app\modules\api\authenticators
 */
class RefreshTokenAuth extends AuthMethod
{
	/**
	 * {@inheritdoc}
	 */
	public function authenticate($user, $request, $response): ?IdentityInterface
	{
		try {
			$grant = AuthController::getRequestGrantType($request);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (Throwable) {
			return null;
		}

		if ($grant instanceof GrantTypeIssue) {
			return null;
		}

		/** @var IdentityInterface $identityClass */
		$identityClass = $user->identityClass;
		$identity      = $identityClass::findIdentityByAccessToken($grant->getRefreshToken(), RefreshTokenType::class);
		if (null === $identity || $identity->isTechUser || !$user->login($identity)) {
			return null;
		}

		return $identity;
	}
}