<?php
declare(strict_types = 1);

namespace app\components\authorization;

use app\models\sys\users\Users;
use cusodede\jwt\JwtHttpBearerAuth as JwtHttpBearerAuthAlias;
use Throwable;
use Yii;
use yii\web\IdentityInterface;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Class FakeJwtBearerAuth
 */
class FakeJwtBearerAuth extends JwtHttpBearerAuthAlias {

	/**
	 * {@inheritdoc}
	 */
	public $pattern = '/^User\s+(.*?)$/';

	/**
	 * @inheritDoc
	 */
	public function authenticate($user, $request, $response):?IdentityInterface {
		if ((null === $authHeader = $request->getHeaders()->get($this->header)) || !preg_match($this->pattern, $authHeader, $matches)) return null;

		try {
			$token = $matches[1];
		} catch (Throwable $e) {
			$token = null;
			$this->fail($response, $e);
		}

		if (null === $identity = Users::findOne($token)) $this->fail($response);

		if (!$user->login($identity)) {
			$this->fail($response);
		}

		return $identity;
	}

	/**
	 * @param Response $response
	 * @param Throwable|null $e
	 * @throws UnauthorizedHttpException
	 * @noinspection PhpSameParameterValueInspection Приглушаю - фейковый класс, тут так нормально
	 */
	private function fail(Response $response, ?Throwable $e = null):void {
		if (null !== $e) {
			Yii::error($e, static::class);
		}

		$this->challenge($response);
		$this->handleFailure($response);
	}

}