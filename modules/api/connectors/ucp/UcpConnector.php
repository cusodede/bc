<?php /** @noinspection PropertyAnnotationInspection - атрибуты инициализируются не в самом конструкторе, а в методах, дёргаемых конструктором. Странно, но ладно. */
declare(strict_types = 1);

namespace app\modules\api\connectors\ucp;

use Yii;
use Throwable;
use yii\helpers\ArrayHelper;
use app\modules\api\connectors\BaseHttpConnector;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception as HttpClientException;
use RuntimeException;
use Exception;

/**
 * Class UcpConnector
 * @package app\modules\api\connectors\ucp
 *
 * @property-read array $dataServices
 * @property-read string $token
 * @property-read array $authServices
 */
class UcpConnector extends BaseHttpConnector
{
	/**
	 * Url сервиса аутентификации.
	 * @var string
	 */
	private string $authService;

	/**
	 * Url сервиса данных.
	 * @var string
	 */
	private string $dataService;

	/**
	 * @var string
	 */
	private string $authToken;

	/**
	 * getenv('UCP_LOGIN')
	 * @var string
	 */
	private string $login;

	/**
	 * getenv('UCP_PASSWORD')
	 * @var string
	 */
	private string $password;

	/**
	 * Сервисы UCP, никто не знает, как формируются эти URL, пока оставлю тут.
	 */
	public const SERVICE_SUBSCRIBER 			= '/0001/subscriber/subscriber_no/';
	public const SERVICE_SUBSCRIBER_NUMBER		= '/0001/subscriber/services/subscriber_no/';
	public const SERVICE_CUSTOMER_MANAGEMENT 	= '/0001/customer_management/customer_account/itype/msisdn/uid/';
	public const SERVICE_CUSTOMER_BALANCES 		= '/0001/customer_management/customer_account_balances/itype/msisdn/uid/';

	/**
	 * UcpConnector constructor.
	 * @param array $config
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function __construct(array $config)
	{
		$params = ArrayHelper::getValue(Yii::$app->params, 'ucp.dev');
		$this->login = ArrayHelper::getValue($config, 'login');
		$this->password = ArrayHelper::getValue($config, 'password');

		if (null === $params || null === $this->login || null === $this->password) {
			throw new InvalidConfigException('Не заданы параметры коннектора');
		}

		parent::__construct($params, $config);

		$this->setAuthService($this->getAuthServices());
		$this->setAuthToken($this->getToken());
		$this->setDataService($this->getDataServices());
	}

	/**
	 * Возвращает массив доступных сервисов для аутентификации.
	 * @return array
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	private function getAuthServices(): array
	{
		$response = $this->getClient()
			->get('/serviceinfo', ['path' => 'ucp:bauth'], [], ['sslVerifyPeer' => false])
			->send();

		$services = $response->isOk ? ArrayHelper::getValue($response->data, 'ucp.bauth') : null;
		if (null === $services) {
			throw new RuntimeException('Ошибка получения сервисов аутентификации ' . $response->toString());
		}
		return $services;
	}

	/**
	 * Время жизни токена, в спецификации не нашёл, оставим 3 минуты.
	 * @return string
	 */
	private function getToken(): string
	{
		return Yii::$app->cache->getOrSet(
			'api:ucp:token',
			function(): string {
				$response = $this->getClient()
					->createRequest()
					->setMethod('post')
					->setUrl(
						$this->authService . '/login?'
						. http_build_query(['sys' => 'bauth', 'login' => $this->login, 'password' => $this->password])
					)
					->send();

				$token = $response->isOk ? ArrayHelper::getValue($response->data, 'token') : null;
				if (null === $token) {
					throw new RuntimeException(
						'Запрос на получение токена от UCP выполнился с ошибкой: '
						. $response->toString()
					);
				}
				return $token;
			}, 180
		);
	}

	/**
	 * Получение доступных сервисов источников данных.
	 * @return array
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	private function getDataServices(): array
	{
		$response = $this->getClient()
			->get('/serviceinfo', ['path' => 'ucp:ucp_query'], ['bauth-token' => $this->authToken])
			->send();

		$services = $response->isOk ? ArrayHelper::getValue($response->data, 'ucp.ucp_query') : null;
		if (null === $services) {
			throw new RuntimeException('Ошибка получения сервисов источников данных ' . $response->toString());
		}
		return $services;
	}

	/**
	 * Получение информации по абоненту.
	 * @param string $service Сервис UCP, смотри константы.
	 * @param string $uid Телефонный номер абонента или его uid, зависит от сервиса.
	 * @return array
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function getSubscriberInfo(string $service, string $uid): array
	{
		$response = $this->getClient()
			->createRequest()
			->setMethod('get')
			->setUrl($this->dataService . $service . $uid)
			->setHeaders(['bauth-token' => $this->authToken])
			->send();

		$data = $response->isOk ? ArrayHelper::getValue($response->data, 'data') : null;
		if (null === $data) {
			throw new RuntimeException('Ошибка получения информации от источников данных ' . $response->toString());
		}

		return $response->data;
	}

	/**
	 * @param array $authServices
	 * @throws Exception
	 */
	private function setAuthService(array $authServices): void
	{
		$this->authService = ArrayHelper::getValue($authServices, array_rand($authServices));
	}

	/**
	 * @param string $authToken
	 */
	private function setAuthToken(string $authToken): void
	{
		$this->authToken = $authToken;
	}

	/**
	 * @param array $dataServices
	 * @throws Exception
	 */
	private function setDataService(array $dataServices): void
	{
		$this->dataService = ArrayHelper::getValue($dataServices, array_rand($dataServices));
	}

	/**
	 * @param string $login
	 */
	public function setLogin(string $login): void
	{
		$this->login = $login;
	}

	/**
	 * @param string $password
	 */
	public function setPassword(string $password): void
	{
		$this->password = $password;
	}
}