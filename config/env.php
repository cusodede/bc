<?php
declare(strict_types = 1);

if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) {
	return require $localConfig;
}

defined('YII_DEBUG') || define('YII_DEBUG', false);
defined('YII_ENV_DEV') || define('YII_ENV_DEV', false);
defined('YII_ENV') || define('YII_ENV', 'prod');
