<?php
declare(strict_types = 1);

return [
	'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname='. getenv('MYSQL_DATABASE'),
	'username' => getenv('MYSQL_ROOT_USERNAME'),
	'password' => getenv('MYSQL_ROOT_PASSWORD'),
	'charset' => 'utf8',

	// Schema cache options (for production environment)
	'enableSchemaCache' => true,
	'schemaCacheDuration' => 0,
	'schemaCache' => 'cache',
];
