<?php
declare(strict_types = 1);

namespace app\modules\api\signatures;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class Factory
 * @package app\modules\api\signatures
 */
final class SignatureServiceFactory
{
	/**
	 * @param string $app
	 * @return SignatureService|null
	 * @throws InvalidConfigException
	 */
	public static function build(string $app): ?SignatureService
	{
		$options = ArrayHelper::getValue(Yii::$app->params, "$app.signatureOptions");
		if (null === $options) {
			return null;
		}

		/** @noinspection PhpParamsInspection неявное преобразование */
		return new SignatureService(
			Instance::ensure(ArrayHelper::getValue($options, 'signer',
				new InvalidConfigException("$app signer not set")),
				Signer::class
			),
			Instance::ensure(ArrayHelper::getValue($options, 'key',
				new InvalidConfigException("$app signer key not set")),
				Key::class
			)
		);
	}
}