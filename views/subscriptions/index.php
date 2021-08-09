<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Model $searchModel
 * @var string $modelName
 * @var ControllerTrait $controller
 * @var ActiveDataProvider $dataProvider
 */

use app\components\helpers\Html;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\grid_config\GridConfig;
use pozitronik\traits\traits\ControllerTrait;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title                   = 'Подписки';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= GridConfig::widget([
	'id'   => "{$modelName}-index-grid",
	'grid' => GridView::begin([
		'dataProvider'     => $dataProvider,
		'filterModel'      => $searchModel,
		'panel'            => [
			'heading' => '',
		],
		'toolbar'          => [
			['content' => Html::ajaxModalLink('Создать подписку', $controller::to('create'), ['class' => ['btn btn-success']])]
		],
		'export'           => false,
		'resizableColumns' => true,
		'responsive'       => true,
		'columns'          => [
			[
				'class'    => ActionColumn::class,
				'template' => '<div class="btn-group">{edit}{view}</div>',
				'buttons'  => [
					'edit' => static function(string $url, Model $model): string {
						return Html::ajaxModalLink('<i class="fas fa-edit"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
					'view' => static function(string $url, Model $model): string {
						return Html::ajaxModalLink('<i class="fas fa-eye"></i>', $url, [
							'class' => ['btn btn-sm btn-outline-primary']
						]);
					},
				],
			],
			'id',
			[
				'attribute' => 'partner_id',
				'value'     => 'product.name',
				'label'     => 'Наименование продукта'
			],
			[
				'attribute' => 'partner_id',
				'value'     => 'product.relatedPartner.name',
				'label'     => 'Партнер'
			],
			[
				'attribute' => 'price',
				'value'     => 'product.price',
				'label'     => 'Стоимость'
			],
			'trial_count',
			[
				'class'     => DataColumn::class,
				'attribute' => 'product.created_at',
				'format'    => ['date', 'php:d.m.Y H:i'],
			],
			[
				'class'     => DataColumn::class,
				'attribute' => 'product.updated_at',
				'format'    => ['date', 'php:d.m.Y H:i'],
			],
		],
	])
]) ?>