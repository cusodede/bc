<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use app\components\DolContractRegister;
use app\components\logstash\LogStash;
use app\components\SetUp;
use app\modules\export\ExportModule;
use app\modules\graphql\GraphqlModule;
use app\modules\import\ImportModule;
use app\modules\status\StatusModule;
use pozitronik\filestorage\FSModule;
use yii\caching\FileCache;
use yii\console\controllers\MigrateController;
use yii\log\FileTarget;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';
$queue = require __DIR__.'/queue.php';
$statusRules = require __DIR__.'/status_rules.php';
$importConfig = require __DIR__.'/import_configs.php';
$s3 = require __DIR__.'/s3.php';

$config = [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => [
		'queue_common', 'log', 'graphql', SetUp::class
	],
	'controllerNamespace' => 'app\commands',
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'modules' => [
		'graphql' => [
			'class' => GraphqlModule::class
		],
		's3' => $s3,
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
		],
		'statuses' => [
			'class' => StatusModule::class,
			'params' => [
				'rules' => $statusRules
			]
		],
		'import' => [
			'class' => ImportModule::class,
			'params' => [
				'mappingRules' => $importConfig
			],
		],
		'export' => [
			'class' => ExportModule::class
		],
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
					'except' => [
						'import.branches', 'import.dealers', 'import.channels', 'import.update_seller_status',
						'import.create_reward_rules', 'import.create_update_reward_rules', 'nsis.resend', 'export.pilot', 's3.console'
					],
				],
				[
					'class' => FileTarget::class,
					'categories' => ['recogdol.api'],
					'levels' => ['info', 'error'],
					'logFile' => '@runtime/logs/recogdol/api.log',
					'maxFileSize' => 10240
				],
				[
					'class' => FileTarget::class,
					'categories' => ['rabbit.nsis.dol'],
					'levels' => ['info',],
					'logFile' => '@runtime/logs/rabbit.nsis.dol.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'categories' => ['dol_main.api'],
					'levels' => ['info', 'error'],
					'logFile' => '@runtime/logs/dol_main/api.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'levels' => ['info', 'warning', 'error'],
					'categories' => ['export.pilot'],
					'logFile' => '@runtime/logs/export/export.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'categories' => [
						'import.branches', 'import.dealers', 'import.channels', 'import.update_seller_status',
						'import.create_reward_rules', 'import.create_update_reward_rules', 'import.main'
					],
					'levels' => ['info', 'warning', 'error'],
					'logFile' => '@runtime/logs/import_main/import.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'categories' => ['nsis.resend'],
					'levels' => ['info', 'warning', 'error'],
					'logFile' => '@runtime/logs/nsis/nsis.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => LogStash::class,
					'categories' => ['service.response'],
					'logFile' => 'php://stdout',
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'levels' => ['info'],
					'categories' => ['service.response'],
					'logFile' => '@runtime/logs/service_response.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
				[
					'class' => FileTarget::class,
					'levels' => ['info', 'error'],
					'categories' => ['s3.console'],
					'logFile' => '@runtime/logs/s3/s3.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
			],
		],
		'permissions' => require __DIR__.'/permissions.php',
		'db' => $db,
		'queue_common' => $queue['common'],
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
				'app\modules\notifications\migrations'
			],
		],
	],

	'params' => $params,
];

return $config;
