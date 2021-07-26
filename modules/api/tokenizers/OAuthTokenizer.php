<?php
declare(strict_types = 1);

namespace app\modules\api\tokenizers;

use app\components\helpers\DateHelper;
use app\models\sys\users\UsersTokens;
use app\modules\api\tokenizers\grant_types\BaseGrantType;
use RuntimeException;
use Yii;
use yii\base\Component;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * Class OAuthTokenizer
 * @package app\modules\api\tokenizers
 *
 * @property-read array $tokenData
 * @property-read string $tokenType
 * @property-read string $authToken
 * @property-read string|null $refreshToken
 * @property-read int $expiresIn
 * @property-read int $refreshTokenExpiresIn
 */
abstract class OAuthTokenizer extends Component implements Tokenizer
{
	/**
	 * @var BaseGrantType
	 */
	protected BaseGrantType $_grantType;
	/**
	 * @var UsersTokens|null токен авторизации для доступа к интерфейсу API.
	 */
	protected ?UsersTokens $_authToken = null;
	/**
	 * @var UsersTokens|null токен для сброса ключа авторизации.
	 */
	protected ?UsersTokens $_refreshToken = null;
	/**
	 * @var bool
	 */
	protected bool $_useRefreshToken = true;

	/**
	 * OAuthTokenizer constructor.
	 * @param BaseGrantType $grantType
	 * @param array $config
	 */
	public function __construct(BaseGrantType $grantType, array $config = [])
	{
		parent::__construct($config);

		$this->_grantType = $grantType;
	}

	/**
	 * @return string тип ключа доступа.
	 */
	abstract protected function getTokenType(): string;

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getTokenData(): array
	{
		$this->initTokens();

		$data = ['access_token' => $this->authToken, 'expires_in' => $this->expiresIn];

		if (null !== $this->refreshToken) {
			$data['refresh_token'] = $this->refreshToken;
		}

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthToken(): string
	{
		return $this->_authToken->auth_token;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRefreshToken(): ?string
	{
		return ArrayHelper::getValue($this->_refreshToken, 'auth_token');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExpiresIn(): int
	{
		return 900;
	}

	/**
	 * Устанавливаем дефолтное время жизни рефреш-токена в 1 месяц.
	 * @return int
	 */
	public function getRefreshTokenExpiresIn(): int
	{
		return 2592000;
	}

	/**
	 * Инициализируем и обрабатываем модели токенов.
	 * @throws Exception
	 */
	private function initTokens(): void
	{
		$this->_authToken = $this->getTokenModel();

		if ($this->_useRefreshToken) {
			$this->_refreshToken = $this->getTokenModel(RefreshTokenType::class);
		}

		//проверяем обоснованность запроса на выпуск токенов
		$this->_grantType->validate($this->_authToken, $this->_refreshToken);

		$this->configureToken(
			$this->_authToken,
			$this->getTokenType(),
			$this->getExpiresIn()
		);
		if ($this->_useRefreshToken) {
			$this->configureToken(
				$this->_refreshToken,
				'refresh',
				$this->getRefreshTokenExpiresIn()
			);
		}

		/** @var Transaction $transaction */
		$transaction = Yii::$app->db->beginTransaction();
		if ($this->_authToken->save() && ((null === $this->_refreshToken) || $this->_refreshToken->save())) {
			if (null !== $this->_refreshToken) {
				$this->_authToken->relatedChildTokens = [$this->_refreshToken];
			}

			$transaction->commit();
		} else {
			$transaction->rollBack();

			throw new RuntimeException('Something went wrong');
		}
	}

	/**
	 * @param string|null $type
	 * @return UsersTokens
	 */
	private function getTokenModel(?string $type = null): UsersTokens
	{
		if (null === $type) {
			$type = $this->getTokenType();
		}

		$config = [
			'type_id'    => UsersTokens::TOKEN_TYPES[$type],
			'user_id'    => $this->_grantType->getUser()->id,
			'user_agent' => $this->_grantType->getUserAgent()
		];

		return UsersTokens::findOne($config) ?? new UsersTokens($config);
	}

	/**
	 * @param UsersTokens $token
	 * @param string $prefix
	 * @param int $expiresIn
	 */
	private function configureToken(UsersTokens $token, string $prefix, int $expiresIn = 0): void
	{
		$token->auth_token = $this->generateRandomToken($prefix);
		$token->valid      = ($expiresIn > 0) ? DateHelper::toFormat("+ $expiresIn seconds") : null;
	}

	/**
	 * Схема генерации ключей.
	 * @param string $prefix
	 * @return string
	 */
	protected function generateRandomToken(string $prefix): string
	{
		$prefix = $this->_grantType->getUser()->id . ':' . static::class . ':' . $prefix;

		return sha1(uniqid($prefix . mt_rand(), true));
	}
}