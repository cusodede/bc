<?php
declare(strict_types = 1);

use app\controllers\BillingJournalController;
use app\controllers\ContractsController;
use app\controllers\NotificationTemplatesController;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\RevShareController;
use app\controllers\SiteController;
use app\controllers\UsersController;
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
use app\controllers\AbonentsController;
use yii\web\View;

/**
 * @var View $this
 */

?>

<?= SideBarWidget::widget([
	'items' => [
		[
			'label' => 'Домой',
			'url' => [Url::home()],
			'iconClass' => 'fa-home'
		],
		[
			'label' => 'Партнеры',
			'url' => [PartnersController::to('index')],
			'iconClass' => 'fa-handshake'
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
			'label' => 'Абоненты',
			'url' => [AbonentsController::to('index')],
			'iconClass' => 'fa-phone'
		],
		[
			'label' => 'Ставки рефшеринга',
			'url' => [RevShareController::to('index')],
			'iconClass' => 'fa-percentage'
		],
		[
			'label' => 'Договоры',
			'url' => [ContractsController::to('index')],
			'iconClass' => 'fa-file-alt'
		],
		[
			'label' => 'Шаблоны уведомлений',
			'url' => [NotificationTemplatesController::to('index')],
			'iconClass' => 'fa-envelope-open-text'
		],
		[
			'label' => 'Пользователи',
			'url' => '#',
			'iconClass' => 'fa-users-cog',
			'items' => [
				[
					'label' => 'Все',
					'url' => [UsersController::to('index')],
					'visible' => UsersController::hasPermission('index')
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
				],
				[
					'label' => 'Настройки системы',
					'url' => [SiteController::to('options')]
				]
			],
		],
		[
			'label' => 'История списаний',
			'url' => [BillingJournalController::to('index')],
			'iconClass' => 'fa-ruble-sign',
			'visible' => BillingJournalController::hasPermission('index')
		],
		[
			'label' => 'История подключений',
			'url' => [ProductsController::to('journal')],
			'iconClass' => 'fa-history',
			'visible' => ProductsController::hasPermission('journal')
		]
	]
]) ?>