<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PartnersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use yii\web\View;
use app\models\partners\PartnersSearch;
use yii\data\ActiveDataProvider;
use pozitronik\grid_config\GridConfig;
use kartik\grid\GridView;
use pozitronik\helpers\Utils;
use app\controllers\PartnersController;
use yii\bootstrap4\Html;
use yii\web\JsExpression;
use kartik\grid\DataColumn;
use kartik\grid\ActionColumn;
use app\models\partners\Partners;
use app\assets\ModalHelperAsset;

ModalHelperAsset::register($this);
?>
<?= GridConfig::widget([
	'id' => 'partners-index-grid',
	'grid' => GridView::begin([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'panel' => [
			'heading' => $this->title . (($dataProvider->totalCount > 0) ?
					' (' . Utils::pluralForm($dataProvider->totalCount, ['партнер', 'партнера', 'партнеров']) . ')' : ' (нет партнеров)'),
		],
		'summary' => $searchModel !== null ? Html::a('Добавить партнера', PartnersController::to('create'), [
			'class' => 'btn btn-success summary-content',
			'onclick' => new JsExpression(
				"AjaxModal('" . PartnersController::to('create') . "', 'Partners-modal-create-new');
				event.preventDefault();"
			)
		]) : null,
		'showOnEmpty' => true,
		'emptyText' => Html::a('Добавить партнера', PartnersController::to('create'), [
			'class' => 'btn btn-success',
			'onclick' => new JsExpression(
				"AjaxModal('".PartnersController::to('create') . "', 'Partners-modal-create-new');
				event.preventDefault();"
			)
		]),
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'class' => ActionColumn::class,
				'template' => '{edit}{update-password}',
				'buttons' => [
					'edit' => static function(string $url, Partners $model): string
					{
						return Html::a('<i class="fas fa-edit"></i>', $url, [
							'onclick' => new JsExpression(
								"AjaxModal('$url', '{$model->formName()}-modal-edit-{$model->id}');
								event.preventDefault();"
							)
						]);
					},
				],
			],
			'id',
			'name',
			'inn',
			[
				'class' => DataColumn::class,
				'attribute' => 'created_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
			[
				'class' => DataColumn::class,
				'attribute' => 'updated_at',
				'format' => ['date', 'php:d.m.Y H:i'],
			],
		]
	])
]) ?>
