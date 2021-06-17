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
		$params = ArrayHelper::getValue(Yii::$app->params, 'vet-expert.connector', new InvalidConfigException('Не заданы параметры коннектора для ivi'));

		if (null === $this->_appID = ArrayHelper::remove($params, 'appID')) {
			throw new InvalidConfigException('APP ID для ivi не задан');
		}

		$this->_signatureService = SignatureServiceFactory::build('ivi');

		parent::__construct($params, $config);
	}

	/**
	 * @param ProductOptions $options
	 * @return PurchaseOptionsHandler
	 * @throws Exception
	 * @throws Throwable
	 */
	public function getPurchaseOptions(ProductOptions $options): PurchaseOptionsHandler
	{
		$response = $this->getClient()
			->get(['/billing/v1/purchase/options', 'app_version' => $options->getAppVersion(), 'access_token' => $this->getAccessTokenByPhone($options), 'with_subscription_renewals' => true])
			->send();

		if (!$response->isOk || (null === $result = ArrayHelper::getValue($response->data, 'result'))) {
			throw new RuntimeException('Ошибка при получении опций от ivi: '
				. $response->toString());
		}

		/** @var array $result */
		return new PurchaseOptionsHandler($result);
	}

	/**
	 * @param ProductOptions $options
	 * @param PurchaseOptionsItem $purchaseOptions
	 * @return PurchaseResultHandler
	 * @throws Throwable
	 * @throws Exception
	 */
	public function makePurchase(ProductOptions $options, PurchaseOptionsItem $purchaseOptions): PurchaseResultHandler
	{
		$postData = [
			'app_version'          => $options->getAppVersion(),
			'partner_id'           => $options->getProduct(),
			'access_token'         => $this->getAccessTokenByPhone($options),
			'ps_extra_signed_data' => "ps_transaction_id=1000&sign={$purchaseOptions->getSignParam()}",
			'sign'                 => $purchaseOptions->getSignParam()
		];
		$postData = array_merge(
			$postData,
			[
				'ps_extra_signature' => $this->getSignedParams($postData['ps_extra_signed_data'])
			],
			$purchaseOptions->getPurchaseParams()
		);

		$response = $this->getClient()
			->post(["/billing/v1/purchase/ext/{$this->_appID}", 'app_version' => $options->getAppVersion(), 'access_token' => $postData['access_token']], $postData)
			->send();

		if (!$response->isOk || (null === $result = ArrayHelper::getValue($response->data, 'result'))) {
			throw new RuntimeException('Ошибка при заведении подписки в ivi: '
				. $response->toString());
		}

		/** @var array $result */
		return new PurchaseResultHandler($result);
	}

	/**
	 * Получение токена доступа, привязанного к номеру телефона.
	 * @param ProductOptions $options
	 * @return string|null
	 */
	private function getAccessTokenByPhone(ProductOptions $options): string
	{
		return $this->getAccessByParam('phone', $options->getAbonentPhone(), $options->getAppVersion());
	}

	/**
	 * Получение токена для взаимодействия с API.
	 * @param string $param название поля для получения токена
	 * @param string $value значение поля для получения токена
	 * @param string $appVersion
	 * @return string
	 */
	private function getAccessByParam(string $param, string $value, string $appVersion): string
	{
		return Yii::$app->cache->getOrSet(
			"api:ivi:access_token:{$param}:{$value}",
			function() use ($param, $value, $appVersion) {
				$data = ['partner' => $this->_appID, $param => $value];

				$data['sign']        = $this->getSignedParams($data);
				$data['app_version'] = $appVersion;

				$response = $this->getClient()
					->post("/user/access_token/{$param}/v5", $data)
					->send();
				if (!$response->isOk || (null === $token = ArrayHelper::getValue($response->data, 'result.access_token'))) {
					throw new RuntimeException('Запрос на получение токена от ivi выполнился с ошибкой: '
						. $response->toString());
				}

				return $token;
			});
	}

	/**
	 * Генерация подписи для параметров передачи в API
	 * @param array|string $params
	 * @return string
	 */
	private function getSignedParams($params): string
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