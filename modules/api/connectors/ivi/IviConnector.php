<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ivi;

use app\modules\api\connectors\BaseHttpConnector;
use app\modules\api\signatures\SignatureService;
use app\modules\api\signatures\SignatureServiceFactory;
use pozitronik\helpers\ArrayHelper;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

/**
 * Class IviConnector
 * @package app\modules\api\connectors\ivi
 */
class IviConnector extends BaseHttpConnector
{
	/**
	 * @var string|null идентификатор приложения, генерируемый на стороне Ivi.
	 */
	private ?string $_appID;
	/**
	 * @var SignatureService компонент для подписи body-параметров.
	 */
	private SignatureService $_signatureService;

	/**
	 * IviConnector constructor.
	 * @param array $config
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function __construct(array $config = [])
	{
		/** @var array $params */
		$params = ArrayHelper::getValue(Yii::$app->params, 'ivi.connector', new InvalidConfigException('Не заданы параметры коннектора для ivi'));

		$this->_appID = ArrayHelper::remove($params, 'appID');
		if (null === $this->_appID) {
			throw new InvalidConfigException('APP ID для ivi не задан');
		}

		$this->_signatureService = SignatureServiceFactory::build('ivi');

		parent::__construct($params, $config);
	}

	/**
	 * Получение опций покупки по абоненту. Время жизни опций покупки - один час.
	 * @param IviSubscriptionOptions $options
	 * @return PurchaseOptionsHandler
	 * @throws Exception
	 * @throws Throwable
	 */
	public function getPurchaseOptions(IviSubscriptionOptions $options): PurchaseOptionsHandler
	{
		$this->get(['/billing/v1/purchase/options', 'app_version' => $options->appVersion, 'access_token' => $this->getAccessTokenByPhone($options), 'with_subscription_renewals' => true]);

		$result = $this->extractResponseData('result');
		if (null === $result) {
			throw new RuntimeException('Ошибка при получении опций от ivi: ' . $this->response->toString());
		}

		/** @var array $result */
		return new PurchaseOptionsHandler($result);
	}

	/**
	 * Создание/обновление подписки в ivi. Необходимо предварительно запросить актуальный список опций.
	 * @param IviSubscriptionOptions $options
	 * @param PurchaseOptionsItem $purchaseOptions
	 * @return PurchaseResultHandler
	 * @throws Throwable
	 * @throws Exception
	 * @see getPurchaseOptions()
	 */
	public function processSubscription(IviSubscriptionOptions $options, PurchaseOptionsItem $purchaseOptions): PurchaseResultHandler
	{
		$postData = [
			'app_version'          => $options->appVersion,
			'partner_id'           => $options->productId,
			'access_token'         => $this->getAccessTokenByPhone($options),
			'ps_extra_signed_data' => "ps_transaction_id={$options->transactionId}&sign={$purchaseOptions->getSignParam()}",
			'sign'                 => $purchaseOptions->getSignParam()
		];
		$postData = array_merge(
			$postData,
			['ps_extra_signature' => $this->getSignedParams($postData['ps_extra_signed_data'])],
			$purchaseOptions->getPurchaseParams()
		);

		$this->post(["/billing/v1/purchase/ext/{$this->_appID}", 'app_version' => $options->appVersion, 'access_token' => $postData['access_token']], $postData);

		$result = $this->extractResponseData('result');
		if (null === $result) {
			throw new RuntimeException('Ошибка при заведении подписки в ivi: ' . $this->response->toString());
		}

		/** @var array $result */
		return new PurchaseResultHandler($result);
	}

	/**
	 * Получение токена доступа по номеру абонента.
	 * @param IviSubscriptionOptions $options
	 * @return string
	 */
	private function getAccessTokenByPhone(IviSubscriptionOptions $options): string
	{
		return $this->getAccessByParam('phone', $options->phone, $options->appVersion);
	}

	/**
	 * Получение токена для взаимодействия с API. Время жизни токена - бессрочно.
	 * @param string $param название поля для получения токена
	 * @param string $value значение поля для получения токена
	 * @param string $appVersion
	 * @return string
	 * @noinspection PhpSameParameterValueInspection доступна доп. авторизация по почте, оставлено для удобства.
	 */
	private function getAccessByParam(string $param, string $value, string $appVersion): string
	{
		return Yii::$app->cache->getOrSet(
			"api:ivi:access_token:{$param}:{$value}",
			function() use ($param, $value, $appVersion) {
				$data = ['partner' => $this->_appID, $param => $value];

				$data['sign']        = $this->getSignedParams($data);
				$data['app_version'] = $appVersion;

				$this->post("/user/access_token/{$param}/v5", $data);

				$token = $this->extractResponseData('result.access_token');
				if (null === $token) {
					throw new RuntimeException('Запрос на получение токена от ivi выполнился с ошибкой: ' . $this->response->toString());
				}

				return $token;
			});
	}

	/**
	 * Генерация подписи для параметров передачи в API.
	 * @param string|string[] $params
	 * @return string
	 */
	private function getSignedParams(array|string $params): string
	{
		if (is_array($params)) {
			ksort($params);
			array_walk($params, static function(&$item, $key) {
				$item = "{$key}={$item}";
			});
			$params = implode('&', $params);
		}

		return $this->_signatureService->sign($params);
	}
}