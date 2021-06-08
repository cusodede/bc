<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\vet_expert;

use app\modules\api\connectors\BaseHttpConnector;
use pozitronik\helpers\ArrayHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception as HttpClientException;

/**
 * Class VetExpertConnector
 * @package app\modules\api\connectors
 */
class VetExpertConnector extends BaseHttpConnector
{
	private ?string $_authToken;

	/**
	 * VetExpertConnector constructor.
	 * @param array $config
	 */
	public function __construct(array $config = [])
	{
		/** @var array $params */
		$params = ArrayHelper::getValue(Yii::$app->params, 'vet-expert.connector', new InvalidConfigException("Не заданы параметры коннектора"));//мой хелпер умеет так, со стандартным можно делать как ниже, с ArrayHelper::remove()

		if (null === $this->_authToken = ArrayHelper::remove($params, 'authToken')) {
			throw new InvalidConfigException("Токен не задан");
		}

		parent::__construct($params, $config);
	}

	/**
	 * Заведение/обновление подписки абонента.
	 *
	 * @param CallbackParams $params
	 * @param string $error
	 * @return bool
	 * @throws HttpClientException
	 */
	public function callback(CallbackParams $params, string &$error): bool
	{
		$response = $this->getClient()
			->post('/callback', $params->getParams(), ['Authorization' => 'Bearer ' . $this->getToken()])
			->send();

		$responseStatus = ArrayHelper::getValue($response->data, 'success');
		if (null === $responseStatus) {
			$error = 'unknown';
		}

		return (bool)$responseStatus;
	}

	/**
	 * Запрос Bearer-токена. Время жизни токена - 15 минут.
	 * @return string|null
	 */
	private function getToken(): ?string
	{
		return Yii::$app->cache->getOrSet(
			'api:vet-expert:token',
			function() {
				$response = $this->getClient()
					->get('/get_token', null, ['Authorization' => 'Basic ' . $this->_authToken])
					->send();

				return $response->data['token'] ?? null;
			}, 900);
	}
}