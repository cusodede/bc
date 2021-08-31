<?php
declare(strict_types = 1);

use yii\caching\DummyCache;
use yii\db\Connection;
use yii\helpers\ArrayHelper;

/** @noinspection UsingInclusionReturnValueInspection */
$web = require __DIR__.'/web.php';

return ArrayHelper::merge(
	$web,
	[
		'components' => [
			'db' => [
				'class' => Connection::class,
				'dsn' => getenv('TEST_DB_DSN'),
				'username' => getenv('TEST_DB_USER'),
				'password' => getenv('TEST_DB_PASS'),
				'charset' => 'utf8',

				// Schema cache options (for production environment)
				'enableSchemaCache' => false,
				'schemaCacheDuration' => 0,
			],
			'cache' => [
				'class' => DummyCache::class
			],
		]
	]
);
