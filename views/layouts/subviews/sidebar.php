<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\web\View;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\SiteController;
use app\controllers\UsersController;
use app\models\core\prototypes\DefaultController;
use app\modules\history\HistoryModule;
use app\widgets\smartadmin\sidebar\SideBarWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\helpers\Url;
use app\controllers\DbController;
use app\controllers\PartnersController;
use app\controllers\ProductsController;
use app\controllers\SubscriptionsController;

?>

<?= SideBarWidget::widget([
	'items' => [
		[
			'label' => 'Профиль',
			'url' => [Url::home()],
			'iconClass' => 'fa-home'
		],
		[
			'label' => 'Партнеры',
			'url' => [PartnersController::to('index')],
			'iconClass' => 'fa-briefcase'
		],
		[
			'label' => 'Продукты',
			'url' => '#',
			'iconClass' => 'fa-shopping-cart',
			'items' => [
				[
					'label' => 'Все продукты',
					'url' => [ProductsController::to('index')]
				],
				[
					'label' => 'Подписки',
					'url' => [SubscriptionsController::to('index')]
				]
			],
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
					'url' => ReferencesModule::to('references')
				],
				[
					'label' => 'Протокол сбоев',
					'url' => SysExceptionsModule::to('index')
				],
				[
					'label' => 'Процессы на БД',
					'url' => DbController::to('process-list')
				],
				[
					'label' => 'Файловый менеджер',
					'url' => FSModule::to('index')
				],
				[
					'label' => 'История изменений',
					'url' => HistoryModule::to('index')
				],
				[
					'label' => 'Настройки системы',
					'url' => SiteController::to('options')
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
]) ?>