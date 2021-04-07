<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PermissionsCollectionsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\controllers\PermissionsCollectionsController;
use app\models\sys\permissions\active_record\PermissionsCollections;
use app\models\sys\permissions\PermissionsCollectionsSearch;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\helpers\Utils;
use pozitronik\widgets\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

?>
<?= GridConfig::widget([
	'id' => 'permissions-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)"),
		],
		'summary' => null !== $searchModel?Html::a('Новая группа', PermissionsCollectionsController::to('create'), ['class' => 'btn btn-success summary-content']):null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Новая группа', PermissionsCollectionsController::to('create'), ['class' => 'btn btn-success']),
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			'name',
			'comment',
			[
				'class' => DataColumn::class,
				'attribute' => 'relatedPermissions',
				'value' => static function(PermissionsCollections $collections) {
					return BadgeWidget::widget([
						'models' => $collections->relatedPermissions,
						'attribute' => 'name'
					]);
				},
				'format' => 'raw'
			],
		]
	])
]) ?>