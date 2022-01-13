<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) return require $localConfig;

use app\models\sys\permissions\Permissions;

return [
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
		'@app/modules/api/controllers' => '@api',
		'@app/modules/history/controllers' => '@history',/*@ - указываем, что это модуль*/
		'@app/modules/import/controllers' => '@import',
		'@vendor/pozitronik/yii2-references/src/controllers' => '@references',
		'@vendor/pozitronik/yii2-exceptionslogger/src/controllers' => '@sysexceptions',
		'@vendor/pozitronik/yii2-dbmon/src/controllers' => '@db',
		'@vendor/pozitronik/yii2-filestorage/src/controllers' => '@filestorage',
	],
	'grantAll' => [1],/*User ids, that receive all permissions by default*/
	'grant' => [/*перечисление прямых назначений*/
		1 => ['login_as_another_user']
	],
	'permissions' => [//параметры контроллер-экшен-etc в этой конфигурации не поддерживаются
		'system' => [
			'comment' => 'Разрешение на доступ к системным параметрам',
		],
		'login_as_another_user' => [
			'comment' => 'Разрешение авторизоваться под другим пользователем',
		],
	]
];