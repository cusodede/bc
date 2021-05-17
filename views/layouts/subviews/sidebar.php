<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\web\View;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\UsersController;
use app\modules\history\HistoryModule;
use app\widgets\smartadmin\sidebar\SideBarWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\helpers\Url;
use app\controllers\DbController;
use app\controllers\PartnersController;

echo SideBarWidget::widget([
	'items' => [
		[
			'label' => 'Домой',
			'url' => [Url::home()],
			'iconClass' => 'fa-home'
		],
		[
			'label' => 'Парнтнеры',
			'url' => [PartnersController::to('index')],
			'iconClass' => 'fa-briefcase'
		],
		[
			'label' => 'Пользователи',
			'url' => '#',
			'iconClass' => 'fa-users-cog',
			'items' => [
				[
					'label' => 'Все',
					'url' => [UsersController::to('index')]
				]
			],
		],
		[
			'label' => 'Доступы',
			'url' => '#',
			'iconClass' => 'fa-lock',
			'items' => [
				[
					'label' => 'Редактор разрешений',
					'url' => [PermissionsController::to('index')]
				],
				[
					'label' => 'Группы разрешений',
					'url' => [PermissionsCollectionsController::to('index')]
				],
			],
		],
		[
			'label' => 'Система',
			'url' => '#',
			'iconClass' => 'fa-wrench',
			'items' => [
				[
					'label' => 'Справочники',
					'url' => [ReferencesModule::to('references')]
				],
				[
					'label' => 'Протокол сбоев',
					'url' => [SysExceptionsModule::to('index')]
				],
				[
					'label' => 'Процессы на БД',
					'url' => [DbController::to('process-list')]
				],
				[
					'label' => 'Файловый менеджер',
					'url' => [FSModule::to('index')]
				],
				[
					'label' => 'История изменений',
					'url' => [HistoryModule::to('index')]
				]
			],
		],
		[
			'label' => 'REST API',
			'url' => '#',
			'iconClass' => 'fa-cloud',
			'items' => [
				[
					'label' => 'Пользователи',
					'url' => ['/api/users'],
				]
			]
		],
	]
]);
