<?php
declare(strict_types = 1);
/**
 * @var View $this
 */

use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\SiteController;
use app\controllers\UsersController;
use app\components\web\DefaultController;
use app\models\sys\users\Users;
use app\modules\history\HistoryModule;
use app\modules\s3\S3Module;
use app\widgets\smartadmin\sidebar\SideBarWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use pozitronik\dbmon\DbMonitorModule;
use yii\helpers\Url;
use yii\web\View;

?>

<?= SideBarWidget::widget([
	'items' => [
		[
			'label' => 'Домой',
			'url' => [Url::home()],
			'iconClass' => 'fa-home'
		],
		[
			'label' => UsersController::Title(),
			'iconClass' => 'fa-users-cog',
			'url' => [UsersController::to('index')],
			'visible' => UsersController::hasPermission('index')
			//'visible' => UsersController::hasPermission() для проверки доступа ко всему контроллеру и отключения всего списка
		],
		[
			'label' => 'Прототипирование',
			'url' => '#',
			'iconClass' => 'fa-digging',
			'items' => DefaultController::MenuItems()
		],
		[
			'label' => 'Доступы',
			'url' => '#',
			'iconClass' => 'fa-lock',
			'items' => [
				[
					'label' => 'Редактор разрешений',
					'url' => [PermissionsController::to('index')],
					'visible' => PermissionsController::hasPermission('index')
				],
				[
					'label' => 'Группы разрешений',
					'url' => [PermissionsCollectionsController::to('index')],
					'visible' => PermissionsCollectionsController::hasPermission('index')
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
					'url' => [ReferencesModule::to('references')],
					'visible' => Users::Current()->hasUrlPermission(ReferencesModule::to('references')),
					'iconClass' => 'fa-list-ol',
				],
				[
					'label' => 'Импорт',
					'url' => '#',
					'iconClass' => 'fa-file-import',
					'visible' => Users::Current()->hasPermission(['do_import']),
					'items' => [
					],
				],
				[
					'label' => 'Экспорт',
					'url' => '#',
					'iconClass' => 'fa-file-export',
					'visible' => Users::Current()->hasPermission(['do_export']),
					'items' => [
					],
				],
				[
					'label' => 'Протокол сбоев',
					'url' => [SysExceptionsModule::to('index')],
					'visible' => Users::Current()->hasUrlPermission(SysExceptionsModule::to('index')),
					'iconClass' => 'fa-debug',
				],
				[
					'label' => 'Процессы на БД',
					'url' => [DbMonitorModule::to('process-list')],
					'visible' => Users::Current()->hasUrlPermission(DbMonitorModule::to('db/process-list')),
					'iconClass' => 'fa-database',
				],
				[
					'label' => 'Файловый менеджер',
					'url' => [FSModule::to('index')],
					'visible' => Users::Current()->hasUrlPermission(FSModule::to('index')),
					'iconClass' => 'fa-save',
				],
				[
					'label' => 'Облачное хранилище',
					'url' => '#',
					'iconClass' => 'fa-cloud-download',
					'visible' => Users::Current()->hasPermission(['do_s3']),
					'items' => [
						[
							'label' => 'Файлы',
							'url' => [S3Module::to('test')],
							'visible' => true
						]
					],
				],
				[
					'label' => 'История изменений',
					'url' => [HistoryModule::to('index')],
					'visible' => Users::Current()->hasUrlPermission(HistoryModule::to('index')),
					'iconClass' => 'fa-history',
				],
				[
					'label' => 'Настройки системы',
					'url' => [SiteController::to('options')],
					'visible' => SiteController::hasPermission('options'),
					'iconClass' => 'fa-sliders-v',
				]
			],
		]
	]
]) ?>
