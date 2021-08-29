<?php
declare(strict_types = 1);

use yii\caching\DummyCache;
use yii\helpers\ArrayHelper;
use yii\web\AssetManager;

/** @noinspection UsingInclusionReturnValueInspection */
$web = require __DIR__.'/web.php';

return ArrayHelper::merge(
	$web,
	[
		'components' => [
			'db' => [
				'class' => 'yii\db\Connection',
				'dsn' => 'mysql:host=mysql;dbname=test',
				'username' => 'root',
				'password' => 'password',
				'charset' => 'utf8',

				// Schema cache options (for production environment)
				'enableSchemaCache' => false,
				'schemaCacheDuration' => 0,
			],
			'cache' => [
				'class' => DummyCache::class
			],
//			'assetManager' => [
//				'class' => AssetManager::class,
//				'basePath' => '@webroot/web/assets',
//			]
		]
	]
);
