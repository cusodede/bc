<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsCollectionsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use app\controllers\PermissionsCollectionsController;
use app\controllers\PermissionsController;
use app\controllers\UsersController;
use app\models\sys\permissions\active_record\Permissions;
use app\models\sys\permissions\active_record\PermissionsCollections;
use app\models\sys\permissions\PermissionsCollectionsSearch;
use app\models\sys\users\Users;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use yii\web\View;
use app\assets\PermissionsCollectionsAsset;

PermissionsCollectionsAsset::register($this);

$this->title = 'Группы разрешений';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= GridConfig::widget([
	'id'   => 'permissions-collections-index-grid',
	'grid' => GridView::begin([
		'dataProvider'     => $dataProvider,
		'filterModel'      => $searchModel,
		'panel'            => [
			'heading' => '',
		],
		'toolbar'          => [
			[
				'content' => Html::ajaxModalLink(
						'Новая группа',
						PermissionsCollectionsController::to('create'),
						['class' => ['btn btn-success mr-2']]
					) .
					Html::a("Редактор разрешений", PermissionsController::to('index'), ['class' => 'btn float-left btn-info'])
			]
		],
		'export'           => false,
		'resizableColumns' => true,
		'responsive'       => true,
		'columns'          => [
			[
				'class'    => ActionColumn::class,
				'template' => '{edit}',
				'buttons'  => [
					'edit' => static function(string $url, PermissionsCollections $model) {
						return Html::a('<i class="fa fa-edit"></i>', $url, [
							'onclick' => new JsExpression("AjaxModal('$url', '{$model->formName()}-modal-edit-{$model->id}');event.preventDefault();")
						]);
					},
				],
			],
			'id',
			[
				'class' => DataColumn::class,
				'attribute' => 'default',
				'format' => 'boolean'
			],
			'name',
			'comment',
			[
				'class'     => DataColumn::class,
				'attribute' => 'permission',
				'label'     => 'Включённые доступы',
				'value'     => static function(PermissionsCollections $collections) {
					return BadgeWidget::widget([//прямые
							'items'     => $collections->relatedPermissions,
							'subItem'   => 'name',
							'options'   => function($mapAttributeValue, Permissions $item) {
								$url = PermissionsController::to('edit', ['id' => $item->id]);
								return [//навешиваем модальный редактор
									'onclick' => new JsExpression("AjaxModal('$url', '{$item->formName()}-modal-edit-{$item->id}');event.preventDefault();")
								];
							},
							'urlScheme' => [PermissionsController::to('edit'), 'id' => 'id']//вдобавок к модалке оставляем ссылку для прямого перехода
						]) . BadgeWidget::widget([//через группы
							'items'     => $collections->relatedPermissionsViaSlaveGroups,
							'subItem'   => 'name',
							'options'   => function($mapAttributeValue, Permissions $item) {
								$url = PermissionsController::to('edit', ['id' => $item->id]);
								return [//навешиваем модальный редактор
									'onclick' => new JsExpression("AjaxModal('$url', '{$item->formName()}-modal-edit-{$item->id}');event.preventDefault();")
								];
							},
							'urlScheme' => [PermissionsController::to('edit'), 'id' => 'id']
						]);
				},
				'format'    => 'raw'
			],
			[
				'class'     => DataColumn::class,
				'attribute' => 'relatedUsers',
				'format'    => 'raw',
				'value'     => static function(PermissionsCollections $collections) {
					return BadgeWidget::widget([
						'items'     => $collections->relatedUsersRecursively,
						'subItem'   => 'username',
						'options'   => function($mapAttributeValue, Users $item) {
							$url = UsersController::to('view', ['id' => $item->id]);
							return [//навешиваем модальный просмотр
								'onclick' => new JsExpression("AjaxModal('$url', '{$item->formName()}-modal-view-{$item->id}');event.preventDefault();")
							];
						},
						'urlScheme' => [UsersController::to('view'), 'id' => 'id']
					]);
				}
			]
		]
	])
]) ?>