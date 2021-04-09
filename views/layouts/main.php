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
use app\models\sys\users\CurrentUserHelper;
use app\widgets\search\SearchWidget;
use pozitronik\filestorage\FSModule;
use pozitronik\helpers\Utils;
use pozitronik\sys_exceptions\SysExceptionsModule;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
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
	<title><?= $this->title ?></title>
	<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
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
				'url' => Url::home()
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
				'label' => 'Система',
				'items' => [
					[
						'label' => 'Редактор разрешений',
						'url' => PermissionsController::to('index')
					],
					[
						'label' => 'Группы разрешений',
						'url' => PermissionsCollectionsController::to('index')
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
				'label' => CurrentUserHelper::model()->username,
				'options' => [
					'class' => 'pull-right'
				],
				'items' => [
					'<li class="dropdown-header">'.CurrentUserHelper::model()->comment.'</li>',
					[
						'label' => "Профиль",
						'url' => '#',
						'options' => [
							'onclick' => new JsExpression('AjaxModal("'.UsersController::to('profile', ['id' => CurrentUserHelper::Id()]).'", "users-modal-profile-'.CurrentUserHelper::Id().'")')
						]
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

<?php $this->endBody(); ?>
</body>
<?php $this->endPage(); ?>
</html>