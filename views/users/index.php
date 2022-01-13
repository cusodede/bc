<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\GridHelperAsset;
use app\assets\ModalHelperAsset;
use app\components\grid\ActionColumn;
use app\components\grid\widgets\toolbar_filter_widget\ToolbarFilterWidget;
use app\components\helpers\Html;
use app\controllers\UsersController;
use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use app\widgets\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use yii\web\View;

ModalHelperAsset::register($this);
GridHelperAsset::register($this);

$id = 'users-index-grid';
?>

<?= GridConfig::widget([
	'id' => $id,
	'grid' => GridView::begin([
		'id' => $id,
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'filterOnFocusOut' => false,
		'panel' => [
			'heading' => false,
		],
		'replaceTags' => [
			'{optionsBtn}' => ToolbarFilterWidget::widget(['content' => '{options}']),
			'{totalCount}' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']):"Нет пользователей",
			'{newRecord}' => ToolbarFilterWidget::widget([
				'label' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']):"Нет пользователей",
				'content' => Html::link('Новый пользователь', UsersController::to('create'), ['class' => 'btn btn-success'])
			]),
			'{filterBtn}' => ToolbarFilterWidget::widget(['content' => Html::button("<i class='fa fa-filter'></i>", ['onclick' => new JsExpression('setFakeGridFilter("#'.$id.'")'), 'class' => 'btn btn-info'])]),
		],
		'toolbar' => [
			'{filterBtn}'
		],
		'panelBeforeTemplate' => '{optionsBtn}{newRecord}{toolbarContainer}{before}<div class="clearfix"></div>',
		'summary' => null,
		'showOnEmpty' => true,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}{update-password}{login-as-another-user}</div>',
				'buttons' => [
					'update-password' => static fn(string $url) => Html::link('<i class="fas fa-lock"></i>', $url, [
							'class' => 'btn btn-sm btn-outline-primary',
							'data' => [
								'trigger' => 'hover',
								'toggle' => 'tooltip',
								'placement' => 'top',
								'original-title' => 'Обновить пароль'
							]
						]
					),
					'login-as-another-user' => /*todo: вытащить в виджет кнопку авторизации и кнопку выхода*/ static fn(string $url, Users $model) => (method_exists(Yii::$app->user, 'isLoginAsAnotherUser')
						?Html::link('<i class="fas fa-sign-in-alt"></i>', UsersController::to('login-as-another-user', ['userId' => $model->id]), [
							'class' => 'btn btn-sm btn-outline-primary',
							'data' => [
								'trigger' => 'hover',
								'toggle' => 'tooltip',
								'placement' => 'top',
								'original-title' => 'Авторизоваться под пользователем'
							]
						],
							Html::NO
						)
						:Html::link('<i class="fas fa-question-square"></i>', '#', ['title' => 'Не поддерживается (не сконфигурирован WebUser?)', 'class' => ['btn btn-sm btn-outline-primary']], Html::NO))
				],
			],
			'id',
			[
				'class' => DataColumn::class,
				'attribute' => 'username',
				'format' => 'raw',
				'value' => static fn(Users $user) => BadgeWidget::widget([
					'items' => $user->username,
					'innerPrefix' => $user->isAllPermissionsGranted()?'<i class="fas fa-crown color-danger-500" title="All permissions granted via config"></i>':false
				])
			],
			'login',
			[
				'class' => DataColumn::class,
				'attribute' => 'create_date',
				'format' => 'datetime'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'relatedPhones',
				'label' => 'Телефоны',
				'format' => 'raw',
				'value' => static fn(Users $user) => BadgeWidget::widget([
					'items' => $user->relatedPhones,
					'subItem' => 'phone'
				])
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'allUserPermission',
				'label' => 'Доступы',
				'format' => 'raw',
				'value' => static fn(Users $user) => BadgeWidget::widget([
					'items' => $user->allPermissions(),
					'subItem' => 'name',
					'innerPrefix' => $user->isAllPermissionsGranted()?'<i class="fas fa-crown color-danger-500" title="All permissions granted via config"></i>':false
				])
			]
		]
	])
]) ?>
