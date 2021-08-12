<?php
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
 * @property-read mixed $subscriberInfo
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
	 * UcpConnector constructor.
	 * @param array $config
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function __construct(array $config = [])
	{
		$params = ArrayHelper::getValue(Yii::$app->params, 'ucp.dev');
		if (null === $params) {
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
						. http_build_query(['sys' => 'bauth', 'login' => 'XXX', 'password' => 'XXX'])
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
	 * @return array
	 * @throws HttpClientException
	 * @throws InvalidConfigException
	 */
	public function getSubscriberInfo(): array
	{
		$response = $this->getClient()
			->createRequest()
			->setMethod('get')
			->setUrl($this->dataService . '/0001/subscriber/subscriber_no/9037935871')
			->setHeaders(['bauth-token' => $this->authToken])
			->send();
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
}