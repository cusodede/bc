<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use app\assets\SmartAdminThemeAssets;
use app\models\sys\permissions\Permissions;
use app\models\sys\users\Users;
use app\models\sys\users\WebUser;
use app\modules\history\HistoryModule;
use app\modules\status\StatusModule;
use app\modules\graphql\GraphqlModule;
use kartik\dialog\DialogBootstrapAsset;
use kartik\editable\EditableAsset;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\models\ErrorHandler;
use simialbi\yii2\rest\Connection;
use kartik\grid\Module as GridModule;
use odannyc\Yii2SSE\LibSSE;
use pozitronik\filestorage\FSModule;
use pozitronik\grid_config\GridConfigModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\caching\FileCache;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;
use yii\log\FileTarget;
use yii\rest\UrlRule;
use yii\swiftmailer\Mailer;
use yii\web\JsonParser;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';
$statusRules = require __DIR__.'/status_rules.php';

$config = [
	'id' => 'basic',
	'name' => 'Beeline Cabinet',
	'language' => 'ru-RU',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log', 'history'],
	'homeUrl' => '/users/profile',//<== строка, не массив
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'modules' => [
		'gridview' => [
			'class' => GridModule::class
		],
		'sysexceptions' => [
			'class' => SysExceptionsModule::class,
			'defaultRoute' => 'index'
		],
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
		'gridconfig' => [
			'class' => GridConfigModule::class
		],
		'references' => [
			'class' => ReferencesModule::class,
			'defaultRoute' => 'references',
			'params' => [
				'baseDir' => [
					'@app/models/ref_subscription_categories/active_record'
				],
			],
		],
		'history' => [
			'class' => HistoryModule::class,
			'defaultRoute' => 'index'
		],
		'graphql' => [
			'class' => GraphqlModule::class,
			'defaultRoute' => 'graphql',
		],
		'statuses' => [
			'class' => StatusModule::class,
			'params' => [
				'rules' => $statusRules
			]
		],
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'cjhjrnsczxj3tpmzyd5jgeceyekb0fyfyf_',
			'parsers' => [
				'application/json' => JsonParser::class
			]
		],
		'cache' => [
			'class' => FileCache::class,
//			'class' => DummyCache::class//todo cache class autoselection
		],
		'user' => [
			'class' => WebUser::class,
			'identityClass' => Users::class,
			'enableAutoLogin' => true
		],
		'errorHandler' => [
			'class' => ErrorHandler::class,
			'errorAction' => 'site/error'
		],
		'mailer' => [
			'class' => Mailer::class,
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG?3:0,
			'targets' => [
				[
					'class' => FileTarget::class,
					'levels' => ['error', 'warning'],
				],
			],
		],
		'sse' => [
			'class' => LibSSE::class
		],
		'rest' => [
			'class' => Connection::class,
			'baseUrl' => 'http://bc/api',
//			 'auth' => function (Connection $db) {
//			      return 'admin: admin';
//			 },
			// 'auth' => 'Bearer: <mytoken>',
			// 'usePluralisation' => false,
			// 'useFilterKeyword' => false,
			// 'enableExceptions' => true,
			'itemsProperty' => 'items'
		],
		'db' => $db,
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'enableStrictParsing' => false,
			'rules' => [
				['class' => UrlRule::class, 'controller' => 'api/users'],
//				'<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/<_a>',
//				'<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>'
			]
		],
		'permissions' => [
			'class' => Permissions::class,
			/*
			 * Пути к расположениям контроллеров, для подсказок в выбиралках.
			 * Формат:
			 * 	алиас каталога => префикс id
			 * Так проще и быстрее, чем пытаться вычислять префикс из контроллера (в нём id появляется только в момент вызова,
			 * и зависит от множества настроек), учитывая, что это нужно только в админке, и только в выбиралке.
			 */
			'controllerDirs' => [
				'@app/controllers' => '',
				'@app/controllers/api' => 'api'
			],
			'grantAll' => [1]/*User ids, that receive all permissions by default*/
		],
		'assetManager' => [
			'bundles' => [
				BootstrapPluginAsset::class => [
					'js' => []
				],
				BootstrapAsset::class => [
					'css' => [],
				],
				DialogBootstrapAsset::class => [
					'depends' => [
						SmartAdminThemeAssets::class
					]
				],
				EditableAsset::class => [
					'depends' => [
						SmartAdminThemeAssets::class
					]
				]
			]
		]
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => DebugModule::class,
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => GiiModule::class
		//'allowedIPs' => ['127.0.0.1', '::1'],
	];
}

return $config;
