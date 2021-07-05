<?php
declare(strict_types = 1);

namespace app\modules\api\use_cases;

use app\models\sys\users\Users;
use app\models\sys\users\UsersTokens;
use InvalidArgumentException;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\Request;

/**
 * Class InvalidateUserByTokenCase
 * @package app\modules\api\use_cases
 */
class InvalidateUserByTokenCase
{
	/**
	 * @param Users $user
	 * @param string $token
	 * @param Request|null $request
	 * @throws BadRequestHttpException
	 * @throws Throwable
	 * @throws StaleObjectException
	 */
	public function execute(Users $user, string $token, ?Request $request = null): void
	{
		$neededToken = null;
		foreach ($user->relatedUsersTokens as $singleToken) {
			if ($singleToken->auth_token === $token) {
				$neededToken = $singleToken;
				break;
			}
		}

		if (null === $neededToken) {
			throw new InvalidArgumentException('Invalid token provided.');
		}

		if (null !== $request) {
			$this->validateRequest($neededToken, $request);
		}

		$neededToken->relatedMainParentToken->delete();
	}

	/**
	 * Тут настраиваем логику проверки соответствия токена полученному запросу на инвалидацию (ip, user-agent и т.д.).
	 * @param UsersTokens $token
	 * @param Request $request
	 * @throws BadRequestHttpException
	 */
	private function validateRequest(UsersTokens $token, Request $request): void
	{
		if ($token->user_agent !== $request->userAgent) {
			throw new BadRequestHttpException();
		}
	}
}