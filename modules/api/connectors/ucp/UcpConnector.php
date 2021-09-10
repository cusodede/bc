<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ucp;

use Yii;
use app\components\helpers\ArrayHelper;
use app\modules\api\connectors\BaseHttpConnector;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception as HttpClientException;
use RuntimeException;
use Throwable;

/**
 * Class UcpConnector
 * @package app\modules\api\connectors\ucp
 *
 * @property-read array $dataServices
 * @property-read array $authServices
 * @property-read string $token
 */
class UcpConnector extends BaseHttpConnector
{
	private const AUTHORIZATION_HEADER_NAME = 'bauth-token';

	/**
	 * Url сервиса аутентификации.
	 * @var string
	 */
	private string $_authService;

	/**
	 * Url сервиса данных.
	 * @var string
	 */
	private string $_dataService;

	/**
	 * getenv('UCP_LOGIN')
	 * @var string|null
	 */
	private ?string $_login;

	/**
	 * getenv('UCP_PASSWORD')
	 * @var string|null
	 */
	private ?string $_password;

	/**
	 * Сервисы UCP, никто не знает, как формируются эти URL, пока оставлю тут.
	 */
	public const SERVICE_SUBSCRIBER = '/0001/subscriber/subscriber_no/';
	public const SERVICE_SUBSCRIBER_NUMBER = '/0001/subscriber/services/subscriber_no/';
	public const SERVICE_CUSTOMER_MANAGEMENT = '/0001/customer_management/customer_account/itype/msisdn/uid/';
	public const SERVICE_CUSTOMER_BALANCES = '/0001/customer_management/customer_account_balances/itype/msisdn/uid/';

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

		$this->_login    = ArrayHelper::remove($params, 'login');
		$this->_password = ArrayHelper::remove($params, 'password');

		if (null === $params || null === $this->_login || null === $this->_password) {
			throw new InvalidConfigException('Не заданы параметры коннектора');
		}

		parent::__construct($params, $config);

		$this->initAuthService();
		$this->initDataService();
	}

	/**
	 * Получение информации по абоненту.
	 * @param string $service Сервис UCP, смотри константы.
	 * @param string $uid Телефонный номер абонента или его uid, зависит от сервиса.
	 * @return array
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	public function getSubscriberInfo(string $service, string $uid): array
	{
		$this->get($this->_dataService . $service . $uid, null, [static::AUTHORIZATION_HEADER_NAME => $this->token]);

		$data = $this->extractResponseData('data');
		if (null === $data) {
			throw new RuntimeException('Ошибка получения информации от источников данных ' . $this->response->toString());
		}

		//TODO wrap response in handler class
		return $data;
	}

	/**
	 * Инициализация сервиса для получения токена авторизации.
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	private function initAuthService(): void
	{
		$this->_authService = ArrayHelper::getRandomItem($this->getAuthServices());
	}

	/**
	 * Инициализация сервиса источника данных.
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	private function initDataService(): void
	{
		$this->_dataService = ArrayHelper::getRandomItem($this->getDataServices());
	}

	/**
	 * Возвращает массив доступных сервисов для аутентификации.
	 * @return array
	 * @throws HttpClientException
	 * @throws Throwable
	 */
	private function getAuthServices(): array
	{
		/** @noinspection CurlSslServerSpoofingInspection смежная система не использует сертификат */
		$this->get('/serviceinfo', ['path' => 'ucp:bauth'], [], [CURLOPT_SSL_VERIFYPEER => false]);

		return $this->extractResponseData(
			'ucp.bauth',
			new RuntimeException('Ошибка получения сервисов аутентификации ' . $this->response->toString())
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
		$this->get('/serviceinfo', ['path' => 'ucp:ucp_query'], [static::AUTHORIZATION_HEADER_NAME => $this->token]);

		return $this->extractResponseData(
			'ucp.ucp_query',
			new RuntimeException('Ошибка получения сервисов источников данных ' . $this->response->toString())
		);
	}

	/**
	 * Время жизни токена, в спецификации не нашёл, оставим 3 минуты.
	 * @return string
	 */
	public function getToken(): string
	{
		return Yii::$app->cache->getOrSet(
			'api:ucp:token',
			function(): string {
				$this->post([rtrim($this->_authService, '/') . '/login', 'sys' => 'bauth', 'login' => $this->_login, 'password' => $this->_password]);

				return $this->extractResponseData(
					'token',
					new RuntimeException('Запрос на получение токена от UCP выполнился с ошибкой: ' . $this->response->toString())
				);
			},
			180
		);
	}
}