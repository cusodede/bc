<?php
declare(strict_types = 1);

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
		'@app/controllers/api' => 'api'
	],
	'grantAll' => [1],/*User ids, that receive all permissions by default*/
	'grant' => [/*перечисление прямых назначений*/
		1 => ['login_as_another_user', 'some_other_permission']
	],
	'permissions' => [//параметры контроллер-экшен-etc в этой конфигурации не поддерживаются
		'system' => [
			'comment' => 'Разрешение на доступ к системным параметрам',
		],
		'login_as_another_user' => [
			'comment' => 'Разрешение авторизоваться под другим пользователем',
		]
	]
];