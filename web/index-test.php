<?php
declare(strict_types = 1);
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

#load env
Dotenv::createImmutable(__DIR__ . '/../')->safeLoad();

require __DIR__ . '/../config/env.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// NOTE: Make sure this file is not accessible when deployed to production
if (getenv('YII_ENV') !== 'dev') {
	die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

$config = require __DIR__ . '/../config/test.php';

(new yii\web\Application($config))->run();
