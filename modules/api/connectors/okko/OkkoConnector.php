<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\okko;

use app\components\helpers\Utils;
use app\modules\api\connectors\BaseHttpConnector;
use http\Exception\RuntimeException;
use yii\httpclient\Client;
use yii\httpclient\Exception as HttpClientException;
use yii\httpclient\RequestEvent;

/**
 * Class OkkoConnector
 * @package app\modules\api\connectors\okko
 */
class OkkoConnector extends BaseHttpConnector
{
	/**
	 * {@inheritDoc}
	 */
	public function init(): void
	{
		parent::init();

		$this->getClient()->on(
			Client::EVENT_BEFORE_SEND,
			function (RequestEvent $event) {
				$event->request->addData(['trackingId' => $this->generateTrackingId()]);
			}
		);
	}

	/**
	 * @param OkkoPurchaseParams $params
	 * @return string
	 * @throws HttpClientException
	 */
	public function makeSubscribe(OkkoPurchaseParams $params): string
	{
		$this->post('/subscription', $params->toArray());

		if ($this->responseIsOk) {
			return $this->extractResponseData('trackingId');
		}

		throw new RuntimeException();
	}

	/**
	 * @param string $oldValue
	 * @param string $newValue
	 * @return string
	 * @throws HttpClientException
	 */
	public function changeSubscriberPhone(string $oldValue, string $newValue): string
	{
		$this->post('/subscriber_change', compact('oldValue', 'newValue'));

		if ($this->responseIsOk) {
			return $this->extractResponseData('trackingId');
		}

		throw new RuntimeException();
	}

	/**
	 * @return string
	 */
	private function generateTrackingId(): string
	{
		return Utils::gen_uuid();
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getResponseIsOk(): bool
	{
		return parent::getResponseIsOk() && '0' === $this->extractResponseData('resultCode');
	}
}