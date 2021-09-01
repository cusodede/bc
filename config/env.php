<?php
declare(strict_types = 1);

if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) {
	return require $localConfig;
}

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG'));
defined('YII_ENV_DEV') or define('YII_ENV_DEV', getenv('YII_ENV_DEV'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV_DEV') ?:'prod');
