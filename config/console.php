<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use app\components\queue\DbQueue;
use app\components\queue\JobIdHandlingBehavior;
use odannyc\Yii2SSE\LibSSE;
use pozitronik\helpers\ArrayHelper;
use yii\console\controllers\MigrateController;
use pozitronik\filestorage\FSModule;
use yii\caching\FileCache;
use yii\log\FileTarget;
//use yii\gii\Module as GiiModule;
use yii\mutex\MysqlMutex;
use yii\mutex\PgsqlMutex;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log', 'productTicketsQueue'],
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
		'permissions' => require __DIR__.'/permissions.php',
		'db' => $db,
		'productTicketsQueue' => [
			'class' => DbQueue::class,
			'db' => 'db', // DB connection component or its config
			'tableName' => '{{%queue}}', // Table name
			'channel' => 'product_ticket', // Queue channel key
			'mutex' => match (ArrayHelper::getValue(explode(':', $db['dsn']), '0')) {// Mutex used to sync queries
				default => MysqlMutex::class,
				'mysql' => MysqlMutex::class,
				'pgsql' => PgsqlMutex::class
			},
			'deleteReleased' => false,
			'as jobIdHandlingBehavior' => JobIdHandlingBehavior::class
		],
		'sse' => [
			'class' => LibSSE::class
		],
	],
	'controllerMap' => [
		'migrate' => [
			'class' => MigrateController::class,
			'useTablePrefix' => false,
			'templateFile' => '@app/migrations/template/default_migration_template.php',
			'generatorTemplateFiles' => [
				'create_table' => '@app/migrations/template/createTableMigration.php',
				'drop_table' => '@app/migrations/template/dropTableMigration.php',
				'add_column' => '@app/migrations/template/addColumnMigration.php',
				'drop_column' => '@app/migrations/template/dropColumnMigration.php',
				'create_junction' => '@app/migrations/template/createTableMigration.php',
			],
			'migrationNamespaces' => [
				'app\modules\history\migrations',// <== именно неймспейс, не путь
				'app\modules\status\migrations',
				'app\modules\import\migrations',
				'app\modules\notifications\migrations',
				'yii\queue\db\migrations'
			],
		],
	],

	'params' => $params,
];

/*if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => GiiModule::class,
	];
}*/

return $config;
