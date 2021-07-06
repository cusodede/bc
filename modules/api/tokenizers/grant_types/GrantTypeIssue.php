<?php
declare(strict_types = 1);

namespace app\modules\api\tokenizers\grant_types;

use app\models\sys\users\UsersTokens;
use app\modules\api\exceptions\InvalidScopeException;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Request;

/**
 * Class GrantTypeIssue
 * @package app\modules\api\tokenizers\grant_types
 */
class GrantTypeIssue extends BaseGrantType
{
	public const DEFAULT_MAX_NUMBER_OF_TOKENS = 3;

	/**
	 * @var int
	 */
	private int $_maxTokensNumber;

	/**
	 * GrantTypeIssue constructor.
	 * @param Request $request
	 * @throws BadRequestHttpException
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);

		$this->_maxTokensNumber = ArrayHelper::getValue(Yii::$app->params, 'maxTokensNumber', static::DEFAULT_MAX_NUMBER_OF_TOKENS);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRefreshToken(): ?string
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 * @param UsersTokens $authToken
	 * @param UsersTokens|null $refreshToken
	 * @throws ForbiddenHttpException
	 * @throws InvalidScopeException
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
	public function validate(UsersTokens $authToken, ?UsersTokens $refreshToken): void
	{
		if ($authToken->isNewRecord) {
			$this->checkLimitExceeded();
			$statusIsOk = true;
		} else {
			$statusIsOk = (null === $refreshToken) ? !$authToken->isValid() : !$refreshToken->isValid();
		}

		if (!$statusIsOk) {
			throw new InvalidScopeException();
		}
	}

	/**
	 * Проверка на исчерпание лимита на количество выданных токенов для пользователя.
	 * @throws Throwable
	 * @throws StaleObjectException
	 * @throws ForbiddenHttpException
	 */
	private function checkLimitExceeded(): void
	{
		$currTokens = $this->getUser()->relatedMainUsersTokens;

		if (count($currTokens) === $this->_maxTokensNumber) {
			//берем самый "неугодный" токен и удаляем из системы (по CASCADE в БД удалятся также все токены, привязанные к родительскому)
			$this->getUser()->relatedUnpopularUserToken->delete();
		}
	}
}