<?php
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
declare(strict_types = 1);

use app\components\queue\DbQueue;
use app\models\sys\users\WebUser;
use cusodede\jwt\Jwt;
use yii\BaseYii;

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 * Note: To avoid "Multiple Implementations" PHPStorm warning and make autocomplete faster
 * exclude or "Mark as Plain Text" vendor/yiisoft/yii2/Yii.php file
 */
class Yii extends BaseYii {
	/**
	 * @var BaseApplication|WebApplication|ConsoleApplication the application instance
	 */
	public static $app;
}

/**
 * Class BaseApplication
 * Used for properties that are identical for both WebApplication and ConsoleApplication
 *
 * @property WebUser $user
 * @property-read Jwt $jwt
 * @property-read DbQueue $productTicketsQueue
 * @property Queue $queue
 */
abstract class BaseApplication extends yii\base\Application {

}

/**
 * Class WebApplication
 * Include only Web application related components here
 */
class WebApplication extends yii\web\Application {
}

/**
 * Class ConsoleApplication
 * Include only Console application related components here
 */
class ConsoleApplication extends yii\console\Application {
}
