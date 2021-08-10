<?php
declare(strict_types = 1);

namespace app\modules\api\connectors\ucp;

use Yii;
use Throwable;
use pozitronik\helpers\ArrayHelper;
use app\modules\api\connectors\BaseHttpConnector;
use yii\base\InvalidConfigException;

/**
 * Class UcpConnector
 * @package app\modules\api\connectors\ucp
 */
class UcpConnector extends BaseHttpConnector
{
	/**
	 * UcpConnector constructor.
	 * @param array $config
	 * @throws Throwable
	 */
	public function __construct(array $config = [])
	{
		if (null === $params = ArrayHelper::getValue(Yii::$app->params, 'ucp.connector')) {
			throw new InvalidConfigException('Не заданы параметры коннектора');
		}
		parent::__construct($params, $config);
	}
}