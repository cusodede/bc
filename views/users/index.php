<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use app\controllers\UsersController;
use app\models\sys\users\Users;
use app\models\sys\users\UsersSearch;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use pozitronik\grid_config\GridConfig;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;

$this->title                   = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridConfig::widget([
	'id' => 'users-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => '',
		],
		'toolbar' => [
			[
				'content' => Html::ajaxModalLink(
					'Новый пользователь',
					UsersController::to('create'),
					['class' => ['btn btn-success mr-2']]
				)
			]
		],
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}{update-password}{login-as-another-user}</div>',
				'buttons' => [
					'edit' => static function(string $url, Users $model) {
						return Html::ajaxModalLink('<i class="fas fa-edit"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view' => static function(string $url, Users $model) {
						return Html::ajaxModalLink('<i class="fas fa-eye"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'update-password' => static function(string $url, Users $model) {
						return Html::ajaxModalLink('<i class="fas fa-lock"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'login-as-another-user' => static function(string $url, Users $model) {
						return Html::a('<i class="fas fa-sign-in-alt"></i>', UsersController::to('login-as-another-user', ['userId' => $model->id]), [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					}
				],
			],
			'id',
			'username',
			'surname',
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
				'value' => static function(Users $user) {
					return BadgeWidget::widget([
						'items' => $user->relatedPhones,
						'subItem' => 'phone'
					]);
				}
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'allUserPermission',
				'label' => 'Доступы',
				'format' => 'raw',
				'value' => static function(Users $user) {
					return BadgeWidget::widget([
						'items' => $user->allPermissions(),
						'subItem' => 'name'
					]);
				}
			]
		]
	])
]) ?>