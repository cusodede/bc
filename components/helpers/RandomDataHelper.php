<?php
declare(strict_types = 1);

namespace app\components\helpers;

use Yii;
use yii\base\Exception;

/**
 * Class RandomDataHelper
 * @package app\components\helpers
 */
class RandomDataHelper {
	/**
	 * @param int $length
	 * @param string $domain
	 * @return string
	 * @throws Exception
	 */
	public static function getRandomEmail(int $length = 32, string $domain = '@dpl.dpl'):string {
		return Yii::$app->security->generateRandomString($length).$domain;
	}
}
