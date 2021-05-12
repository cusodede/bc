<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $content
 */

use app\assets\AppAsset;
use app\assets\ModalHelperAsset;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\SiteController;
use app\controllers\UsersController;
use app\models\core\prototypes\DefaultController;
use app\models\sys\users\Users;
use app\modules\history\HistoryModule;
use app\widgets\search\SearchWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\helpers\Utils;
use pozitronik\references\ReferencesModule;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use app\controllers\DbController;

AppAsset::register($this);
ModalHelperAsset::register($this);
?>
<!DOCTYPE html>
<?php $this->beginPage(); ?>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="commit" content="<?= Utils::LastCommit() ?>">
	<?= Html::csrfMetaTags() ?>
	<title><?= $this->title ?> [<?= Utils::LastCommit() ?>]</title>
	<?php $this->head(); ?>
</head>

<body>
<?php $this->beginBody(); ?>
<?php if (Yii::$app->user->isGuest || ArrayHelper::getValue(Yii::$app->user->identity, 'is_pwd_outdated', false)): ?>
	<div class="panel panel-trans text-center">
		<div class="panel-heading">
			<h1 class="error-code text-primary">Не пущу!</h1>
		</div>
		<div class="panel-body">
			<p>Пользователь не авторизован</p>
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw text-primary"></i>
			<div class="pad-top"><a class="btn-link text-semibold" href="/">Авторизоваться</a></div>
		</div>
	</div>
<?php else: ?>
	<div class="navigation">
		<?php NavBar::begin([
			'renderInnerContainer' => false,
			'options' => [
				'class' => 'navbar'
			]
		]); ?>
		<?= Nav::widget([
			'items' => [
				[
					'label' => 'Домой',
					'url' => Url::home(true)
				],
				[
					'label' => 'Пользователи',
					'items' => [
						[
							'label' => 'Все',
							'url' => UsersController::to('index')
						]
					],
				],
				[
					'label' => 'Прототипирование',
					'items' => DefaultController::MenuItems()
				],
				[
					'label' => 'Доступы',
					'items' => [
						[
							'label' => 'Редактор разрешений',
							'url' => PermissionsController::to('index')
						],
						[
							'label' => 'Группы разрешений',
							'url' => PermissionsCollectionsController::to('index')
						],
					],
				],
				[
					'label' => 'Система',
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
						]
					],
				],
				[
					'label' => 'REST API',
					'items' => [
						[
							'label' => 'Пользователи',
							'url' => '/api/users',
						]
					]
				],
				SearchWidget::widget(),
				[
					'label' => Users::Current()->username,
					'options' => [
						'class' => 'pull-right'
					],
					'items' => [
						'<li class="dropdown-header">'.Users::Current()->comment.'</li>',
						[
							'label' => "Профиль",
							'url' => UsersController::to('profile', ['id' => Yii::$app->user->id]),
							'options' => [
								'onclick' => new JsExpression('AjaxModal("'.UsersController::to('profile', ['id' => Yii::$app->user->id]).'", "'.Users::Current()->formName().'-modal-profile-'.Yii::$app->user->id.'");event.preventDefault();')
							],
							'encode' => true
						],
						'<li class="divider"></li>',
						[
							'label' => 'Выход',
							'url' => SiteController::to('logout'),
							'options' => [
								'class' => 'pull-right'
							]
						],
					],
				],

			],
			'options' => [
				'class' => 'nav-pills pull-left'
			]
		]) ?>
		<?php NavBar::end(); ?>
	</div>
	<div class="clearfix"></div>
	<div class="boxed">
		<div id="content-container">
			<div id="page-content">
				<?= $content ?>
			</div>
		</div>
	</div>
<?php endif ?>
<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>