<?php
declare(strict_types = 1);

namespace app\modules\api\connectors;

use Exception;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception as HttpClientException;
use yii\httpclient\Response;

/**
 * Class BaseConnector
 * @package app\modules\api\connectors
 *
 * @property-read Client $client
 * @property-read null|Response $response
 * @property-read bool $responseIsOk
 * @property-read string $baseUrl
 */
abstract class BaseHttpConnector extends Component
{
	private Client $_httpClient;

	private ?Response $_response = null;

	/**
	 * BaseHttpConnector constructor.
	 * @param array $clientOptions
	 * @param array $config
	 */
	public function __construct(array $clientOptions = [], array $config = [])
	{
		$this->_httpClient = new Client(array_merge($clientOptions, ['transport' => CurlTransport::class]));
		parent::__construct($config);
	}

	/**
	 * @param array|string $url
	 * @param array|string|null $data
	 * @param array $headers
	 * @param array $options
	 * @throws HttpClientException
	 */
	public function get(
		array|string $url,
		array|string $data = null,
		array $headers = [],
		array $options = []
	): void
	{
		$this->_response = $this->_httpClient->get($url, $data, $headers, $options)->send();
	}

	/**
	 * @param array|string $url
	 * @param array|string|null $data
	 * @param array $headers
	 * @param array $options
	 * @throws HttpClientException
	 */
	public function post(
		array|string $url,
		array|string $data = null,
		array $headers = [],
		array $options = []
	): void
	{
		$this->_response = $this->_httpClient->post($url, $data, $headers, $options)->send();
	}

	/**
	 * @return Client
	 */
	protected function getClient(): Client
	{
		return $this->_httpClient;
	}

	/**
	 * @return Response|null
	 */
	protected function getResponse(): ?Response
	{
		return $this->_response;
	}

	/**
	 * @return bool
	 */
	protected function getResponseIsOk(): bool
	{
		return $this->_response->isOk;
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws Exception
	 */
	protected function extractResponseData(string $key): mixed
	{
		return ArrayHelper::getValue($this->response->data, $key);
	}

	/**
	 * @return string
	 */
	public function getBaseUrl(): string
	{
		return $this->_httpClient->baseUrl;
	}
}