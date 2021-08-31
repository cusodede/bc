<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
use yii\db\Connection;

if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

return [
	'class' => Connection::class,
	'dsn' => getenv('MYSQL_DSN'),
	'username' => getenv('MYSQL_USERNAME'),
	'password' => getenv('MYSQL_PASSWORD'),
	'charset' => 'utf8',

	// Schema cache options (for production environment)
	'enableSchemaCache' => true,
	'schemaCacheDuration' => 60,
	'schemaCache' => 'cache',
];
