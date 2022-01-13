<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var RestDataProvider $dataProvider
 */

use app\assets\ModalHelperAsset;
use app\components\grid\ActionColumn;
use app\components\helpers\Html;
use app\controllers\UsersController;
use app\models\sys\users\Users;
use app\widgets\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use simialbi\yii2\rest\RestDataProvider;
use yii\web\View;

ModalHelperAsset::register($this);
?>

<?= GridConfig::widget([
	'id' => 'users-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'panel' => [
			'heading' => ($dataProvider->totalCount > 0)?Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']):"Нет пользователей",
		],
		'summary' => Html::link('Новый пользователь', UsersController::to('create'), ['class' => 'btn btn-success summary-content']),
		'showOnEmpty' => true,
		'emptyText' => Html::link('Новый пользователь', UsersController::to('create'), ['class' => 'btn btn-success']),
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}</div>',
			],
			'id',
			'username',
			'login',
			[
				'class' => DataColumn::class,
				'attribute' => 'create_date',
				'format' => 'datetime'
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'allUserPermission',
				'format' => 'raw',
				'value' => static fn(Users $user) => BadgeWidget::widget([
					'items' => $user->allPermissions(),
					'subItem' => 'name'
				])
			]
		]
	])
]) ?>