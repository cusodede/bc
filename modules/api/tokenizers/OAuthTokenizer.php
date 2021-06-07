<?php
declare(strict_types = 1);

namespace app\modules\api\tokenizers;

use app\models\sys\users\Users;
use app\models\sys\users\UsersTokens;
use app\modules\api\tokenizers\grant_types\GrantTypeInterface;
use app\modules\api\tokenizers\grant_types\GrantTypeRefresh;
use InvalidArgumentException;
use pozitronik\helpers\DateHelper;
use RuntimeException;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * Class BaseTokenizer
 * @package app\modules\api\tokenizers
 */
abstract class OAuthTokenizer implements Tokenizer
{
	public const ACCESS_TOKEN_DEFAULT_LIFETIME = 3600;

	/**
	 * @var Users
	 */
	protected Users $_user;
	/**
	 * @var GrantTypeInterface
	 */
	protected GrantTypeInterface $_grantType;
	/**
	 * @var UsersTokens токен авторизации для доступа к интерфейсу API.
	 */
	protected UsersTokens $_authToken;
	/**
	 * @var UsersTokens|null токен для сброса ключа авторизации.
	 */
	protected ?UsersTokens $_refreshToken;

	/**
	 * OAuthTokenizer constructor.
	 * @param Users $user
	 * @param GrantTypeInterface $grantType
	 * @throws Exception
	 */
	public function __construct(Users $user, GrantTypeInterface $grantType)
	{
		$this->_user      = $user;
		$this->_grantType = $grantType;
		if ($grantType instanceof GrantTypeRefresh && (null === $grantType->getRefreshToken())) {
			throw new InvalidArgumentException('refresh_token param is invalid');
		}

		$this->initTokens();
	}

	/**
	 * @return string тип ключа доступа.
	 */
	abstract protected function getTokenType(): string;

	public function getTokenData(): array
	{
		$data = ['access_token' => $this->getToken(), 'expires_in' => $this->getExpiresIn()];
		if (null !== $refreshToken = $this->getRefreshToken()) {
			$data['refresh_token'] = $refreshToken;
		}

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRefreshToken(): ?string
	{
		return ArrayHelper::getValue($this->_refreshToken, 'auth_key');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExpiresIn(): int
	{
		return self::ACCESS_TOKEN_DEFAULT_LIFETIME;
	}

	/**
	 * Инициализируем и обрабатываем модели токенов.
	 * @throws Exception
	 */
	protected function initTokens(): void
	{
		$this->initAuthToken();
		$this->initRefreshToken();
		//проверяем обоснованность запроса на выпуск токенов
		$this->_grantType->validate($this->_authToken, $this->_refreshToken);

		/** @var Transaction $transaction */
		$transaction = Yii::$app->db->beginTransaction();
		if ($this->_authToken->save() && ((null === $this->_refreshToken) || $this->_refreshToken->save())) {
			$transaction->commit();
		} else {
			$transaction->rollBack();

			throw new RuntimeException('Something went wrong');
		}
	}

	/**
	 * Инициализация модели основного токена для взаимодействия с API.
	 */
	protected function initAuthToken(): void
	{
		$model = $this->getModelByType($this->getTokenType());

		$exp = strtotime("+ {$this->getExpiresIn()} seconds");

		$model->auth_token = $this->generateRandomToken("{$this->_user->id}:" . static::class);
		$model->valid      = DateHelper::from_unix_timestamp($exp);

		$this->_authToken = $model;
	}

	/**
	 * Инициализация модели refresh токена.
	 */
	protected function initRefreshToken(): void
	{
		$model = $this->getModelByType(RefreshTokenType::class);

		$model->auth_token = $this->generateRandomToken("{$this->_user->id}:" . static::class . ":refresh");

		$this->_refreshToken = $model;
	}

	/**
	 * Схема генерации ключей.
	 * @param string $prefix
	 * @return string
	 */
	protected function generateRandomToken(string $prefix = ''): string
	{
		return sha1(uniqid($prefix . mt_rand(), true));
	}

	/**
	 * @param string $type
	 * @return UsersTokens
	 */
	private function getModelByType(string $type): UsersTokens
	{
		$config = ['user_id' => $this->_user->id, 'type_id' => UsersTokens::TOKEN_TYPES[$type]];

		return UsersTokens::findOne($config) ?? new UsersTokens($config);
	}
}