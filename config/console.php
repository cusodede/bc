<?php
declare(strict_types = 1);

use pozitronik\filestorage\FSModule;
use yii\caching\FileCache;
use yii\log\FileTarget;
use \yii\gii\Module as GiiModule;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'app\commands',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'modules' => [
		'filestorage' => [
			'class' => FSModule::class,
			'defaultRoute' => 'index',
			'params' => [
				'tableName' => 'sys_file_storage',//используемая таблица хранения метаданных
				'tableNameTags' => 'sys_file_storage_tags',//используемая таблица хранения тегов
				'base_dir' => '@app/web/uploads/',//каталог хранения файлов
				'models_subdirs' => true,//файлы каждой модели кладутся в подкаталог с именем модели
				'name_subdirs_length' => 2//если больше 0, то файлы загружаются в подкаталоги по именам файлов (параметр регулирует длину имени подкаталогов)
			]
		]
	],
	'components' => [
		'cache' => [
			'class' => FileCache::class,
		],
		'log' => [
			'targets' => [
				[
					'class' => FileTarget::class,
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => $db,
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => GiiModule::class,
	];
}

return $config;
