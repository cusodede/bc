<?php
declare(strict_types = 1);

return [
	'class' => 'yii\db\Connection',
	'dsn' => 'mysql:host=localhost;dbname=bc',
	'username' => 'root',
	'password' => 'root',
	'charset' => 'utf8',

	// Schema cache options (for production environment)
	'enableSchemaCache' => true,
	'schemaCacheDuration' => 0,
	'schemaCache' => 'cache',
];
