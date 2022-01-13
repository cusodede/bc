<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use app\assets\SmartAdminThemeAssets;
use app\components\authorization\CheckPasswordOutdated;
use app\components\logstash\LogStash;
use app\components\SetUp;
use app\components\tracer\TracerImplementation;
use app\models\sys\permissions\filters\PermissionFilter;
use app\models\sys\users\Users;
use app\models\sys\users\WebUser;
use app\modules\export\ExportModule;
use app\modules\graphql\GraphqlModule;
use app\modules\history\HistoryModule;
use app\modules\import\ImportModule;
use app\modules\notifications\NotificationsModule;
use app\modules\status\StatusModule;
use kartik\dialog\DialogBootstrapAsset;
use kartik\editable\EditableAsset;
use kartik\grid\Module as GridModule;
use odannyc\Yii2SSE\LibSSE;
use OpenTracing\GlobalTracer;
use pozitronik\dbmon\DbMonitorModule;
use pozitronik\filestorage\FSModule;
use pozitronik\grid_config\GridConfigModule;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\models\ErrorHandler;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\debug\Module as DebugModule;
use yii\gii\Module as GiiModule;
use yii\log\FileTarget;
use yii\rest\UrlRule;
use yii\swiftmailer\Mailer;
use yii\web\JsonParser;
use yii\web\Response;

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';
$queue = require __DIR__.'/queue.php';
$statusRules = require __DIR__.'/status_rules.php';
$importConfig = require __DIR__.'/import_configs.php';
$jwt = require __DIR__.'/jwt.php';
$cache = require __DIR__.'/cache.php';
$s3 = require __DIR__.'/s3.php';

$config = [
	'id' => 'basic',
	'name' => 'Basic Platform',
	'language' => 'ru-RU',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log', 'history', 'queue_common', CheckPasswordOutdated::class, SetUp::class],
	'homeUrl' => '/home/home',//<== строка, не массив
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
		'@modules' => '@app/modules',
	],
	'modules' => [
		'gridview' => [
			'class' => GridModule::class,
			'as access' => [
				'class' => PermissionFilter::class,
			],
		],
		'sysexceptions' => [
			'class' => SysExceptionsModule::class,
			'defaultRoute' => 'index',
			'as access' => [
				'class' => PermissionFilter::class,
			],
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
			],
			'as access' => [
				'class' => PermissionFilter::class,
			],
		],
		'gridconfig' => [
			'class' => GridConfigModule::class
		],
		'references' => [
			'class' => ReferencesModule::class,
			'defaultRoute' => 'references',
			'params' => [
				'baseDir' => [
					'@app/models/',
					'@app/modules/calculator/models/references/'
				],
				'excludeDir' => [
					'@app/models/test',
				]
			],
			'as access' => [
				'class' => PermissionFilter::class,
			],
		],
		'history' => [
			'class' => HistoryModule::class,
			'defaultRoute' => 'index',
			'as access' => [
				'class' => PermissionFilter::class,
			],
		],
		'statuses' => [
			'class' => StatusModule::class,
			'params' => [
				'rules' => $statusRules
			]
		],
		'notifications' => [
			'class' => NotificationsModule::class
		],
		'graphql' => [
			'class' => GraphqlModule::class
		],
		'recogdol' => [
			'params' => [
				'connection' => [
					'host' => 'recogdol.beelinetst.ru',
					'sslCertificate' => false,
					'user' => 'dol',
					'password' => 'Qwerty123'
				]
			]
		],
		'db' => [
			'class' => DbMonitorModule::class,
			'as access' => [
				'class' => PermissionFilter::class,
			],
		],
		'import' => [
			'class' => ImportModule::class,
			'params' => [
				'mappingRules' => $importConfig
			]
		],
		'export' => [
			'class' => ExportModule::class
		],
		'recaptcha' => [
			'fullUrl' => getenv('RECAPTCHA_FULL_URL'),
			'keys' => [
				'ios' => getenv('RECAPTCHA_KEY_IOS'),
				'android' => getenv('RECAPTCHA_KEY_ANDROID'),
				'web' => getenv('RECAPTCHA_KEY_WEB')
			]
		],
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'cjhjrnsc1zxjtpmzyd4jgefceyekb15fyfyf8',
			'parsers' => [
				'application/json' => JsonParser::class
			]
		],
		'response' => [
			'class' => Response::class,
			'on beforeSend' => static function($event) {
				$tracer = GlobalTracer::get();
				if ($tracer instanceof TracerImplementation) {
					$span = $tracer->getSpan();
					if ($span) {
						Yii::$app->response->headers->set('X-Trace-ID', $span->getContext()->getTraceId());
					}
				}
			},
		],
		'cache' => $cache,
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
					'except' => ['s3.web'],
					'maskVars' => require __DIR__ . '/mask_log_vars.php',
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
					'categories' => ['dol_main.api'],
					'levels' => ['info', 'error'],
					'logFile' => '@runtime/logs/dol_main/api.log',
					'maxFileSize' => 10240
				],
				[
					'class' => FileTarget::class,
					'levels' => ['info', 'error'],
					'categories' => ['s3.web'],
					'logFile' => '@runtime/logs/s3/s3.log',
					'maxFileSize' => 10240,
					'logVars' => []
				],
			],
		],
		'sse' => [
			'class' => LibSSE::class
		],
		'db' => $db,
		'queue_common' => $queue['common'],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'enableStrictParsing' => false,
			'rules' => [
				['class' => UrlRule::class, 'controller' => 'api/users'],
				'graphql' => 'graphql/graphql/index',
				'db/process-list' => 'db/db/process-list',
				'db/kill/<process_id>' => 'db/db/kill',
				'register' => 'site/register',
				'login' => 'site/login',
				'restore-password' => 'site/restore-password',
				'update-password' => 'site/update-password',
				'login-sms' => 'site/login-sms',
//				'<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/<_a>',
//				'<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>'
			]
		],
		'permissions' => require __DIR__.'/permissions.php',
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
		],
		'jwt' => $jwt,
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => DebugModule::class,
		'allowedIPs' => ['*'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => GiiModule::class,
		'allowedIPs' => ['*'],
	];
}

return $config;
