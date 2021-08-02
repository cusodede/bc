<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

return [
	'class' => 'yii\db\Connection',
	'dsn' => genenv(name: 'DB_DSN'),
	'username' => genenv(name: 'DB_USERNAME'),
	'password' => genenv(name: 'DB_PASSWORD'),
	'charset' => 'utf8',

	// Schema cache options (for production environment)
	'enableSchemaCache' => true,
	'schemaCacheDuration' => 60,
	'schemaCache' => 'cache',
];
