<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\common;

use app\modules\api\connectors\BaseHttpConnector;
use app\modules\api\signatures\SignatureServiceFactory;
use pozitronik\helpers\ArrayHelper;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception as HttpClientException;

/**
 * Коннектор для однотипных интеграция с партнерами, которым мы предоставляем описание требуемого интерфейса взаимодействия.
 * @property-read string $app
 */
abstract class CommonConnector extends BaseHttpConnector
{
	public const APP_IVI = 'ivi';
	public const APP_VET_EXPERT = 'vet-expert';
	public const APP_OKKO = 'okko';

	private ?string $_login;
	private ?string $_password;

	/**
	 * VetExpertConnector constructor.
	 * @param array $config
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function __construct(array $config = [])
	{
		/** @var array $params */
		$params = ArrayHelper::getValue(Yii::$app->params, "{$this->app}.connector", new InvalidConfigException('Не заданы параметры коннектора'));

		$this->_login    = ArrayHelper::remove($params, 'login');
		$this->_password = ArrayHelper::remove($params, 'password');

		if (null === $this->_login || null === $this->_password) {
			throw new InvalidConfigException('Не заданы доступы для авторизации.');
		}

		parent::__construct($params, $config);
	}

	/**
	 * Идентификатор партнера интеграции.
	 * @return string
	 */
	abstract public function getApp(): string;

	/**
	 * Заведение/обновление подписки абонента.
	 * @param CommonSubscriptionParams $params
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	public function makeSubscribe(CommonSubscriptionParams $params): void
	{
		$this->post('/callback', $this->adjustParamsWithSign($params->toArray()), ['Authorization' => 'Bearer ' . $this->getToken()]);

		if (true !== $this->extractResponseData('success')) {
			throw new RuntimeException('Запрос на подписку для VetExpert выполнился с ошибкой: ' . $this->response->toString());
		}
	}

	/**
	 * Запрос Bearer-токена. Время жизни токена - 15 минут.
	 * @return string
	 * @throws RuntimeException
	 */
	private function getToken(): string
	{
		$cacheKey = "api:{$this->app}:token";
		return Yii::$app->cache->getOrSet(
			$cacheKey,
			function() {
				$this->get('/get_token', null, [], [CURLOPT_USERPWD => "{$this->_login}:{$this->_password}"]);

				$token = $this->extractResponseData('token');
				if (null === $token) {
					throw new RuntimeException('Запрос на получение токена от VetExpert выполнился с ошибкой: ' . $this->response->toString());
				}

				return $token;
			}, 900);
	}

	/**
	 * @param array $params
	 * @return array массив параметров, дополненный подписью всех параметров.
	 * @throws InvalidConfigException
	 */
	private function adjustParamsWithSign(array $params): array
	{
		$signatureService = SignatureServiceFactory::build($this->app);
		if (null !== $signatureService) {
			ksort($params);
			array_walk($params, static function(&$item, $key) {
				$item = "{$key}={$item}";
			});

			$params['sign'] = $signatureService->sign(implode('&', $params));
		}

		return $params;
	}
}