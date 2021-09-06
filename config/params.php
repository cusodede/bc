<?php
declare(strict_types = 1);

/*При наличии одноимённого файла в подкаталоге /local конфигурация будет взята оттуда*/
if (file_exists($localConfig = __DIR__.DIRECTORY_SEPARATOR.'local'.DIRECTORY_SEPARATOR.basename(__FILE__))) {
	return require $localConfig;
}

use app\models\sys\permissions\active_record\PermissionsCollections;
use app\models\sys\permissions\Permissions;
use app\models\sys\users\Users;
use cusodede\jwt\Jwt;

return [
	'bsVersion' => '4',
	'frontUrl' => 'http://prpl.apps.k01.vimpelcom.ru',
	'searchConfig' => [
		'Users' => [//<== алиас модели
			'class' => Users::class,//<== FQN-название ActiveRecord-класса
			'templateView' => '@app/views/users/search-template', //<== путь до шаблона отображения результата поиска
			/*
			 * можно вписать строку шаблона напрямую, этот параметр приоритетнее
			 * 'template' => '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="suggestion-secondary">{{controller}}</div><div class="suggestion-links"><a href="'.PermissionsController::to('edit').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Редактировать<a/></div><div class="clearfix"></div></div>',
			 */
			'header' => 'Пользователи', //<== заголовок в поисковом выводе
			//'limit' => 5,// <== лимит поиска,
			//'url' => AjaxController::to('search') // <== Url входящего поискового экшена
			'attributes' => [// <== поисковые атрибуты, см. SearchHelper::Search $searchAttributes
				'comment',
				'email'
			]
		],
		'Permissions' => [
			'class' => Permissions::class,
			'templateView' => '@app/views/permissions/search-template',
			'header' => 'Доступы',
			'attributes' => [
				'name',
				'controller'
			]
		],
		'PermissionsCollections' => [
			'class' => PermissionsCollections::class,
			'templateView' => '@app/views/permissions-collections/search-template',
			'header' => 'Группы доступов',
			'attributes' => [
				'name',
			]
		]
	],
	'ivi' => [
		'connector' => [
			'appID'   => 'partnertest',
			'baseUrl' => 'https://api.ivi.ru/mobileapi'
		],
		'productMap' => [
			1 => ['productId' => 101, 'appVersion' => 8424]
		],
		'signatureOptions' => [
			'signer'    => Jwt::RS256,
			'signerKey' => ''
		]
	],
	'vet-expert' => [
		'connector' => [
			'baseUrl'  => 'https://erp.vetexpert.ru/beeline',
			'login'    => 'beeline',
			'password' => 'cY_LCw6-puS86CSgWZ22MV2dhNt'
		]
	],
	'ucp' => [
		'dev' => [
			'baseUrl' => 'http://tst-ucpsct001.vimpelcom.ru:1480'
		],
		'prod' => [
			'baseUrl' => 'https://ucp-digest.vimpelcom.ru:1480'
		]
	]
];
